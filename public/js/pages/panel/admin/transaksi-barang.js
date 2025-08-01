$(document).ready(function () {
  let produkData = [];
  let kategoriData = [];
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  let currentProduct = null;
  let isLoading = false;
  let hasMoreProducts = true;
  let currentPage = 1;

  // Inisialisasi komponen
  const productModal = M.Modal.init(document.getElementById("product-modal"));
  const cartModal = M.Modal.init(document.getElementById("cart-modal"));
  const checkoutModal = M.Modal.init(document.getElementById("checkout"));
  M.FormSelect.init(document.querySelectorAll("select"));
  M.Collapsible.init(document.querySelectorAll(".collapsible"));

  // Load data produk dan kategori
  function loadInitialData() {
    isLoading = true;
    $(".preloader").slideDown();

    Promise.all([
      $.get(origin + "/api/produk?page=1&limit=12"),
      $.get(origin + "/api/kategori"),
    ])
      .then(function (responses) {
        produkData = responses[0].data || responses[0];
        kategoriData = responses[1];

        renderAllProducts(produkData);
        renderCategories(kategoriData);
        updateCartUI();

        // Cek jika ada lebih banyak produk
        if (
          responses[0].next_page_url ||
          (responses[0].data && responses[0].data.length === 12)
        ) {
          hasMoreProducts = true;
          currentPage = 2;
        } else {
          hasMoreProducts = false;
        }
      })
      .catch(function (error) {
        console.error("Error loading data:", error);
        M.toast({ html: "Gagal memuat data", classes: "red" });
      })
      .finally(function () {
        isLoading = false;
        $(".preloader").slideUp();
      });
  }

  // Render kategori sebagai chips
  function renderCategories(categories) {
    const chipsContainer = $("#category-chips");
    chipsContainer.empty();

    // Tambahkan chip "Semua"
    chipsContainer.append(`
      <div class="chip category-chip active" data-category="all">Semua</div>
    `);

    // Tambahkan chip untuk setiap kategori
    categories.forEach((category) => {
      chipsContainer.append(`
        <div class="chip category-chip" data-category="${category.kode}">${category.nama}</div>
      `);
    });
  }

  // Render semua produk
  function renderAllProducts(products) {
    const productList = $("#product-list");

    if (currentPage === 1) {
      productList.empty();
    }

    if (products.length === 0 && currentPage === 1) {
      productList.append(`
        <div class="col s12 center-align">
          <p class="grey-text">Tidak ada produk yang ditemukan</p>
        </div>
      `);
      return;
    }

    products.forEach((product) => {
      productList.append(createProductCard(product));
    });
  }

  // Buat card produk
  function createProductCard(product) {
    const isNew =
      new Date(product.created_at) >
      new Date(Date.now() - 30 * 24 * 60 * 60 * 1000);
    const hasDiscount = product.diskon > 0;

    return `
      <div class="col s12 m6 l4">
        <div class="card hoverable product-card" data-id="${product.id}">
          <div class="card-image">
            <img src="${origin}/api/v2/source/storage/${product.gambar || "no-image.jpg"}" alt="${product.nama}">
            ${isNew ? '<span class="badge-new">BARU</span>' : ""}
            ${
              hasDiscount
                ? `<span class="badge-sale">${product.diskon}% OFF</span>`
                : ""
            }
          </div>
          <div class="card-content">
            <span class="card-title truncate">${product.nama}</span>
            <div class="rating" style="color: #ffab00; font-size: 14px; margin: 5px 0;">
              ${generateStarRating(product.rating || 0)}
              <span style="color: #9e9e9e; font-size: 12px;">(${
                product.review_count || 0
              })</span>
            </div>
            ${
              hasDiscount
                ? `
              <p>
                <span class="price-original">Rp ${formatCurrency(
                  product.harga
                )}</span>
                <span class="price-discounted">Rp ${formatCurrency(
                  (product.harga * (100 - product.diskon)) / 100
                )}</span>
              </p>
            `
                : `<p><strong>Rp ${formatCurrency(product.harga)}</strong></p>`
            }
            <p class="truncate">${product.deskripsi}</p>
          </div>
          <div class="card-action">
            <a href="#" class="blue-text view-product">Detail</a>
            <a href="#" class="green-text add-to-cart">+ Keranjang</a>
          </div>
        </div>
      </div>
    `;
  }

  // Generate star rating
  function generateStarRating(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

    let stars = "";

    for (let i = 0; i < fullStars; i++) {
      stars += '<i class="material-icons tiny">star</i>';
    }

    if (hasHalfStar) {
      stars += '<i class="material-icons tiny">star_half</i>';
    }

    for (let i = 0; i < emptyStars; i++) {
      stars += '<i class="material-icons tiny">star_border</i>';
    }

    return stars;
  }

  // Format mata uang
  function formatCurrency(amount) {
    return parseFloat(amount).toLocaleString("id-ID");
  }

  // Load lebih banyak produk
  function loadMoreProducts() {
    if (isLoading || !hasMoreProducts) return;

    isLoading = true;
    $("#loading-products").show();

    $.get(`${origin}/api/produk?page=${currentPage}&limit=12`)
      .then(function (response) {
        const newProducts = response.data || response;
        produkData = [...produkData, ...newProducts];
        renderAllProducts(newProducts);

        if (response.next_page_url || newProducts.length === 12) {
          hasMoreProducts = true;
          currentPage++;
        } else {
          hasMoreProducts = false;
        }
      })
      .catch(function (error) {
        console.error("Error loading more products:", error);
        M.toast({ html: "Gagal memuat produk tambahan", classes: "red" });
      })
      .finally(function () {
        isLoading = false;
        $("#loading-products").hide();
      });
  }

  // Update tampilan keranjang
  function updateCartUI() {
    const cartItems = $("#cart-items");
    const cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);

    $("#cart-count").text(cartCount);

    if (cartCount === 0) {
      $("#cart-count").hide();
    } else {
      $("#cart-count").show();
    }

    // Simpan ke localStorage
    localStorage.setItem("cart", JSON.stringify(cart));
  }

  // Event delegation untuk produk
  $(document).on("click", ".view-product", function (e) {
    e.preventDefault();
    const productId = $(this).closest(".product-card").data("id");
    currentProduct = produkData.find((p) => p.id == productId);

    if (currentProduct) {
      // Set data produk di modal
      $("#modal-product-name").text(currentProduct.nama);
      $("#modal-product-description").text(currentProduct.deskripsi);

      // Set rating
      $("#product-rating").html(`
        ${generateStarRating(currentProduct.rating || 0)}
        <span style="color: #9e9e9e; font-size: 14px;">(${
          currentProduct.review_count || 0
        } ulasan)</span>
      `);

      // Set harga
      if (currentProduct.diskon > 0) {
        $("#product-price").html(`
          <span class="price-original">Rp ${formatCurrency(
            currentProduct.harga
          )}</span>
          <span class="price-discounted">Rp ${formatCurrency(
            (currentProduct.harga * (100 - currentProduct.diskon)) / 100
          )}</span>
          <span class="badge-sale">${currentProduct.diskon}% OFF</span>
        `);
      } else {
        $("#product-price").html(
          `<strong>Rp ${formatCurrency(currentProduct.harga)}</strong>`
        );
      }

      // Set gambar produk
      const imagesContainer = $("#product-images");
      imagesContainer.empty();

      if (currentProduct.gambar) {
        const images = Array.isArray(currentProduct.gambar)
          ? currentProduct.gambar
          : [currentProduct.gambar];

        images.forEach((img) => {
          imagesContainer.append(`
            <li>
              <img src="${origin}/api/v2/source/storage/${img}">
            </li>
          `);
        });
      } else {
        imagesContainer.append(`
          <li>
            <img src="${origin}/api/v2/source/storage/no-image.jpg">
          </li>
        `);
      }

      // Inisialisasi slider gambar
      M.Slider.init(document.querySelector(".slider"), { indicators: true });

      // Set detail produk
      const detailsContainer = $("#product-details");
      detailsContainer.empty();

      detailsContainer.append(`
        <tr>
          <td>Kategori</td>
          <td>${
            kategoriData.find((k) => k.kode === currentProduct.kategori_kode)
              ?.nama || "-"
          }</td>
        </tr>
        <tr>
          <td>Stok</td>
          <td>${currentProduct.stok}</td>
        </tr>
        <tr>
          <td>Berat</td>
          <td>${currentProduct.berat || "0"} gram</td>
        </tr>
        <tr>
          <td>Kondisi</td>
          <td>${currentProduct.kondisi || "Baru"}</td>
        </tr>
      `);

      // Set ulasan produk
      const reviewsContainer = $("#product-reviews");
      reviewsContainer.empty();

      // Contoh ulasan (dalam implementasi nyata, ini akan diambil dari API)
      const sampleReviews = [
        {
          user: "Pelanggan 1",
          rating: 5,
          comment: "Produk bagus, sesuai deskripsi",
          date: "2023-05-15",
        },
        {
          user: "Pelanggan 2",
          rating: 4,
          comment: "Pengiriman cepat, produk OK",
          date: "2023-05-10",
        },
      ];

      sampleReviews.forEach((review) => {
        reviewsContainer.append(`
          <div class="review" style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
            <div style="font-weight: bold;">${review.user}</div>
            <div style="color: #ffab00; margin: 5px 0;">${generateStarRating(
              review.rating
            )}</div>
            <div>${review.comment}</div>
            <div style="color: #9e9e9e; font-size: 12px; margin-top: 5px;">${
              review.date
            }</div>
          </div>
        `);
      });

      // Set jumlah maksimal berdasarkan stok
      $("#product-quantity").val(1).attr("max", currentProduct.stok);

      productModal.open();
    }
  });

  // Tambah ke keranjang
  $(document).on("click", ".add-to-cart", function (e) {
    e.preventDefault();
    const productId = $(this).closest(".product-card").data("id");
    currentProduct = produkData.find((p) => p.id == productId);

    if (currentProduct) {
      addToCart(currentProduct, 1);
    }
  });

  // Tambah ke keranjang dari modal
  $("#add-to-cart-btn").click(function () {
    const quantity = parseInt($("#product-quantity").val());
    if (currentProduct && quantity > 0 && quantity <= currentProduct.stok) {
      addToCart(currentProduct, quantity);
      productModal.close();
      cartModal.open();
    } else {
      M.toast({ html: "Jumlah tidak valid", classes: "red" });
    }
  });

  // Beli sekarang
  $("#buy-now-btn").click(function () {
    const quantity = parseInt($("#product-quantity").val());
    if (currentProduct && quantity > 0 && quantity <= currentProduct.stok) {
      // Kosongkan keranjang dan tambahkan produk ini saja
      cart = [
        {
          product: currentProduct,
          quantity: quantity,
        },
      ];
      updateCartUI();
      productModal.close();
      checkoutModal.open();
      updateCheckoutUI();
    } else {
      M.toast({ html: "Jumlah tidak valid", classes: "red" });
    }
  });

  // Fungsi tambah ke keranjang
  function addToCart(product, quantity) {
    const existingItem = cart.find((item) => item.product.id === product.id);

    if (existingItem) {
      // Jika produk sudah ada di keranjang, tambahkan jumlahnya
      const newQuantity = existingItem.quantity + quantity;
      if (newQuantity > product.stok) {
        M.toast({
          html: `Jumlah melebihi stok yang tersedia (${product.stok})`,
          classes: "red",
        });
        return;
      }
      existingItem.quantity = newQuantity;
    } else {
      // Jika produk belum ada di keranjang, tambahkan item baru
      cart.push({
        product: product,
        quantity: quantity,
      });
    }

    updateCartUI();
    M.toast({
      html: `${product.nama} ditambahkan ke keranjang`,
      classes: "green",
    });
  }

  // Tampilkan modal keranjang
  $("#cart-preview-btn").click(function () {
    updateCartModal();
    cartModal.open();
  });

  // Update modal keranjang
  function updateCartModal() {
    const cartItems = $("#cart-items");
    cartItems.empty();

    if (cart.length === 0) {
      cartItems.append(
        '<p class="center-align grey-text">Keranjang kosong</p>'
      );
      $("#total-amount").text("Rp 0");
      $("#checkout-btn").prop("disabled", true);
      return;
    }

    let total = 0;

    cart.forEach((item, index) => {
      const price =
        item.product.diskon > 0
          ? (item.product.harga * (100 - item.product.diskon)) / 100
          : item.product.harga;

      const subtotal = price * item.quantity;
      total += subtotal;

      cartItems.append(`
        <div class="cart-item row valign-wrapper" data-index="${index}">
          <div class="col s3">
            <img src="${origin}/api/v2/source/storage/${
        item.product.gambar || "no-image.jpg"
      }" 
                 alt="${item.product.nama}" class="responsive-img" width="60">
          </div>
          <div class="col s5">
            <span class="truncate">${item.product.nama}</span>
            <p>Rp ${formatCurrency(price)} x ${item.quantity}</p>
          </div>
          <div class="col s3 right-align">
            <strong>Rp ${formatCurrency(subtotal)}</strong>
          </div>
          <div class="col s1">
            <a href="#" class="red-text remove-item"><i class="material-icons tiny">delete</i></a>
          </div>
        </div>
      `);
    });

    $("#total-amount").text(`Rp ${formatCurrency(total)}`);
    $("#checkout-btn").prop("disabled", false);
  }

  // Hapus item dari keranjang
  $(document).on("click", ".remove-item", function (e) {
    e.preventDefault();
    const index = $(this).closest(".cart-item").data("index");
    const productName = cart[index].product.nama;
    cart.splice(index, 1);
    updateCartUI();
    updateCartModal();
    M.toast({
      html: `${productName} dihapus dari keranjang`,
      classes: "orange",
    });
  });

  // Filter berdasarkan kategori
  $(document).on("click", ".category-chip", function () {
    const category = $(this).data("category");

    // Update active chip
    $(".category-chip").removeClass("active");
    $(this).addClass("active");

    // Filter produk
    if (category === "all") {
      renderAllProducts(produkData);
    } else {
      const filteredProducts = produkData.filter(
        (p) => p.kategori_kode === category
      );
      renderAllProducts(filteredProducts);
    }
  });

  // Sortir produk
  $("#sort-products").change(function () {
    const sortBy = $(this).val();
    let sortedProducts = [...produkData];

    switch (sortBy) {
      case "price-asc":
        sortedProducts.sort((a, b) => {
          const priceA =
            a.diskon > 0 ? (a.harga * (100 - a.diskon)) / 100 : a.harga;
          const priceB =
            b.diskon > 0 ? (b.harga * (100 - b.diskon)) / 100 : b.harga;
          return priceA - priceB;
        });
        break;
      case "price-desc":
        sortedProducts.sort((a, b) => {
          const priceA =
            a.diskon > 0 ? (a.harga * (100 - a.diskon)) / 100 : a.harga;
          const priceB =
            b.diskon > 0 ? (b.harga * (100 - b.diskon)) / 100 : b.harga;
          return priceB - priceA;
        });
        break;
      case "newest":
        sortedProducts.sort(
          (a, b) => new Date(b.created_at) - new Date(a.created_at)
        );
        break;
      case "popular":
        sortedProducts.sort((a, b) => (b.rating || 0) - (a.rating || 0));
        break;
    }

    renderAllProducts(sortedProducts);
  });

  // Pencarian produk
  $("#search-product").on(
    "input",
    debounce(function () {
      const searchTerm = $(this).val().toLowerCase();

      if (searchTerm === "") {
        renderAllProducts(produkData);
        return;
      }

      const filteredProducts = produkData.filter(
        (product) =>
          product.nama.toLowerCase().includes(searchTerm) ||
          product.deskripsi.toLowerCase().includes(searchTerm) ||
          product.kode.toLowerCase().includes(searchTerm)
      );

      renderAllProducts(filteredProducts);
    }, 300)
  );

  // Fungsi debounce untuk pencarian
  function debounce(func, wait) {
    let timeout;
    return function () {
      const context = this;
      const args = arguments;
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(context, args), wait);
    };
  }

  // Update UI checkout
  function updateCheckoutUI() {
    const orderSummary = $("#order-summary");
    orderSummary.empty();

    let subtotal = 0;
    let totalItems = 0;

    cart.forEach((item) => {
      const price =
        item.product.diskon > 0
          ? (item.product.harga * (100 - item.product.diskon)) / 100
          : item.product.harga;

      const itemTotal = price * item.quantity;
      subtotal += itemTotal;
      totalItems += item.quantity;

      orderSummary.append(`
        <div class="row" style="margin-bottom: 10px;">
          <div class="col s6">
            <p>${item.product.nama} (x${item.quantity})</p>
          </div>
          <div class="col s6 right-align">
            <p>Rp ${formatCurrency(itemTotal)}</p>
          </div>
        </div>
      `);
    });

    // Contoh biaya pengiriman (dalam implementasi nyata, ini akan dihitung berdasarkan API)
    const shippingCost = 15000;
    const total = subtotal + shippingCost;

    orderSummary.append(`
      <div class="divider" style="margin: 10px 0;"></div>
      <div class="row">
        <div class="col s6">
          <p>Subtotal (${totalItems} item)</p>
        </div>
        <div class="col s6 right-align">
          <p>Rp ${formatCurrency(subtotal)}</p>
        </div>
      </div>
      <div class="row">
        <div class="col s6">
          <p>Biaya Pengiriman</p>
        </div>
        <div class="col s6 right-align">
          <p>Rp ${formatCurrency(shippingCost)}</p>
        </div>
      </div>
      <div class="divider" style="margin: 10px 0;"></div>
    `);

    $("#checkout-total").text(`Rp ${formatCurrency(total)}`);
    $("#payment-amount").text(`Rp ${formatCurrency(total)}`);
  }

  // Proses checkout
  $("#checkout-form").submit(function (e) {
    e.preventDefault();

    const customerName = $("#customer-name").val().trim();
    const customerEmail = $("#customer-email").val().trim();
    const customerPhone = $("#customer-phone").val().trim();
    const shippingAddress = $("#shipping-address").val().trim();
    const shippingMethod = $("#shipping-method").val();
    const paymentMethod = $("#payment-method").val();

    // Validasi
    if (
      !customerName ||
      !customerEmail ||
      !customerPhone ||
      !shippingAddress ||
      !shippingMethod ||
      !paymentMethod
    ) {
      M.toast({ html: "Harap lengkapi semua field", classes: "red" });
      return;
    }

    if (cart.length === 0) {
      M.toast({ html: "Keranjang belanja kosong", classes: "red" });
      return;
    }
    // Tampilkan loading
    $(".preloader").slideDown();

    // Siapkan data order
    const orderItems = cart.map((item) => ({
      product_id: item.product.id,
      quantity: item.quantity,
      price:
        item.product.diskon > 0
          ? (item.product.harga * (100 - item.product.diskon)) / 100
          : item.product.harga,
    }));

    const orderData = {
      customer_name: customerName,
      customer_email: customerEmail,
      customer_phone: customerPhone,
      shipping_address: shippingAddress,
      shipping_method: shippingMethod,
      payment_method: paymentMethod,
      items: orderItems,
    };

    // Simulasikan pengiriman data ke API (dalam implementasi nyata, ini akan menggunakan AJAX)
    setTimeout(() => {
      $(".preloader").slideUp();

      // Tampilkan notifikasi sukses
      M.toast({
        html: `Pesanan berhasil dibuat! Kode pesanan: #${Math.floor(
          100000 + Math.random() * 900000
        )}`,
        classes: "green",
        displayLength: 10000,
      });

      // Kosongkan keranjang
      cart = [];
      updateCartUI();

      // Reset form
      $("#checkout-form")[0].reset();

      // Tutup modal checkout
      checkoutModal.close();

      // Redirect ke halaman terima kasih (opsional)
      // window.location.href = "/thank-you";
    }, 2000);
  });

  // Load lebih banyak produk saat scroll
  $(window).scroll(function () {
    if (
      $(window).scrollTop() + $(window).height() >
      $(document).height() - 300
    ) {
      loadMoreProducts();
    }
  });

  // Inisialisasi data saat dokumen siap
  loadInitialData();
});
