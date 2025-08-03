$(document).ready(function () {
  // Global variables
  let products = [];
  let categories = [];
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  let currentProduct = null;
  let isLoading = false;
  let hasMore = true;
  let currentPage = 1;
  let selectedCategory = "all";
  let currentSort = "newest";
  let currentSearch = "";

  // API endpoints
  const API_BASE_URL = "http://localhost:8080"; // Replace with your actual API URL
  const CATEGORIES_API = `${API_BASE_URL}/api/kategori`;
  const PRODUCTS_API = `${API_BASE_URL}/api/produk`;

  // Initialize components
  const productModal = M.Modal.init(document.getElementById("product-modal"), {
    onCloseEnd: () => {
      currentProduct = null;
    },
  });

  const cartModal = M.Modal.init(document.getElementById("cart-modal"), {
    onOpenStart: function () {
      updateCartModal();
    },
  });
  const checkoutModal = M.Modal.init(document.getElementById("checkout"), {
    onOpenStart: function () {
      updateCheckoutUI();
    },
  });
  const orderConfirmModal = M.Modal.init(
    document.getElementById("order-confirmation")
  );

  M.FormSelect.init(document.querySelectorAll("select"));
  M.Collapsible.init(document.querySelectorAll(".collapsible"));
  M.Tooltip.init(document.querySelectorAll(".tooltipped"));

  // Load initial data
  function loadInitialData() {
    isLoading = true;
    $("#loading-products").show();

    Promise.all([fetchProducts(), fetchCategories()])
      .then(([productsData, categoriesData]) => {
        products = productsData;
        categories = categoriesData;

        renderCategories();
        renderProducts();
        updateCartUI();
      })
      .catch((error) => {
        console.error("Error loading initial data:", error);
        showToast("Gagal memuat data", "red");
      })
      .finally(() => {
        isLoading = false;
        $("#loading-products").hide();
      });
  }

  // Fetch products from API
  function fetchProducts() {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: PRODUCTS_API,
        method: "GET",
        success: function (response) {
          // Transform the API response to match our expected format
          const transformedProducts = response.map((product) => ({
            id: product.id,
            sku: product.kode,
            name: product.nama,
            description: product.deskripsi,
            images: [product.gambar], // Assuming 'gambar' contains the image filename
            price: parseFloat(product.harga),
            stock: product.stok,
            category: product.kategori_kode,
            categoryName: product.kategori.nama,
            createdAt: product.created_at || new Date().toISOString(),
            // Adding mock data for fields not in your API
            discount: 0, // Your API doesn't have discount info
            discountedPrice: null,
            rating: 4.5, // Mock rating
            reviewCount: 10, // Mock review count
            sold: 0, // Mock sold count
          }));
          resolve(transformedProducts);
        },
        error: function (xhr, status, error) {
          reject(error);
        },
      });
    });
  }

  // Fetch categories from API
  function fetchCategories() {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: CATEGORIES_API,
        method: "GET",
        success: function (response) {
          // Transform the API response to match our expected format
          const transformedCategories = response.map((category) => ({
            id: category.kode,
            name: category.nama,
            description: category.deskripsi,
          }));
          resolve(transformedCategories);
        },
        error: function (xhr, status, error) {
          reject(error);
        },
      });
    });
  }

  // Render categories
  function renderCategories() {
    const container = $("#category-chips");
    container.empty();

    // Add 'All' category
    container.append(`
      <div class="category-chip ${selectedCategory === "all" ? "active" : ""}" 
           data-category="all">Semua</div>
    `);

    // Add other categories
    categories.forEach((category) => {
      container.append(`
        <div class="category-chip ${
          selectedCategory === category.id ? "active" : ""
        }" 
             data-category="${category.id}">${category.name}</div>
      `);
    });
  }

  // Render products
  function renderProducts() {
    const container = $("#products-container");

    if (currentPage === 1) {
      container.empty();
    }

    if (products.length === 0 && currentPage === 1) {
      container.html(`
        <div class="col s12 empty-state">
          <i class="material-icons empty-state-icon">shopping_basket</i>
          <h5>Tidak ada produk yang ditemukan</h5>
          <p class="grey-text">Coba gunakan kata kunci lain atau filter yang berbeda</p>
        </div>
      `);
      return;
    }

    // Filter and sort products
    let filteredProducts = filterAndSortProducts();

    // Render product cards
    filteredProducts.forEach((product) => {
      container.append(createProductCard(product));
    });
  }

  // Filter and sort products based on current filters
  function filterAndSortProducts() {
    let filtered = [...products];

    // Apply category filter
    if (selectedCategory !== "all") {
      filtered = filtered.filter((p) => p.category === selectedCategory);
    }

    // Apply search filter
    if (currentSearch) {
      const searchTerm = currentSearch.toLowerCase();
      filtered = filtered.filter(
        (p) =>
          p.name.toLowerCase().includes(searchTerm) ||
          p.description.toLowerCase().includes(searchTerm) ||
          p.sku.toLowerCase().includes(searchTerm)
      );
    }

    // Apply sorting
    switch (currentSort) {
      case "price-asc":
        filtered.sort((a, b) => a.price - b.price);
        break;
      case "price-desc":
        filtered.sort((a, b) => b.price - a.price);
        break;
      case "newest":
        filtered.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
        break;
      case "popular":
        filtered.sort((a, b) => b.rating - a.rating);
        break;
      case "best-selling":
        filtered.sort((a, b) => b.sold - a.sold);
        break;
    }

    return filtered;
  }

  // Create product card HTML
  function createProductCard(product) {
    // Since your API doesn't have discount info, we'll skip those elements
    return `
      <div class="col s12 m6 l4">
        <div class="card product-card" data-id="${product.id}">
          <div class="product-image-container">
            <img src="${
              API_BASE_URL + "/api/v2/source/storage/" + product.images[0]
            }" alt="${product.name}" class="product-image">
             
          </div>
          <div class="product-content">
            <h5 class="product-title">${product.name}</h5>
            <div class="rating" style="color: #ffab00; margin: 5px 0;">
              ${generateStarRating(product.rating)}
              <span style="color: #9e9e9e; font-size: 12px;">(${
                product.reviewCount
              })</span>
            </div>
            <div class="product-price">
              <span class="current-price">Rp ${formatCurrency(
                product.price
              )}</span>
            </div>
          </div>
          <div class="product-actions">
            <a href="#" class="blue-text view-detail">Detail</a>
            <a href="#" class="green-text add-to-cart">+ Keranjang</a>
          </div>
        </div>
      </div>
    `;
  }

  // Render products
  function renderProducts() {
    const container = $("#products-container");

    if (currentPage === 1) {
      container.empty();
    }

    if (products.length === 0 && currentPage === 1) {
      container.html(`
                <div class="col s12 empty-state">
                    <i class="material-icons empty-state-icon">shopping_basket</i>
                    <h5>Tidak ada produk yang ditemukan</h5>
                    <p class="grey-text">Coba gunakan kata kunci lain atau filter yang berbeda</p>
                </div>
            `);
      return;
    }

    // Event handler untuk tombol lihat keranjang
    $("#cart-preview-btn").click(function (e) {
      e.preventDefault();
      updateCartModal(); // Perbarui tampilan keranjang sebelum dibuka
      cartModal.open(); // Buka modal keranjang
    });

    // Filter and sort products
    let filteredProducts = filterAndSortProducts();

    // Render product cards
    filteredProducts.forEach((product) => {
      container.append(createProductCard(product));
    });
  }

  // Filter and sort products based on current filters
  function filterAndSortProducts() {
    let filtered = [...products];

    // Apply category filter
    if (selectedCategory !== "all") {
      filtered = filtered.filter((p) => p.category === selectedCategory);
    }

    // Apply search filter
    if (currentSearch) {
      const searchTerm = currentSearch.toLowerCase();
      filtered = filtered.filter(
        (p) =>
          p.name.toLowerCase().includes(searchTerm) ||
          p.description.toLowerCase().includes(searchTerm) ||
          p.sku.toLowerCase().includes(searchTerm)
      );
    }

    // Apply sorting
    switch (currentSort) {
      case "price-asc":
        filtered.sort(
          (a, b) =>
            (a.discountedPrice || a.price) - (b.discountedPrice || b.price)
        );
        break;
      case "price-desc":
        filtered.sort(
          (a, b) =>
            (b.discountedPrice || b.price) - (a.discountedPrice || a.price)
        );
        break;
      case "newest":
        filtered.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
        break;
      case "popular":
        filtered.sort((a, b) => b.rating - a.rating);
        break;
      case "best-selling":
        filtered.sort((a, b) => b.sold - a.sold);
        break;
    }

    return filtered;
  }

  // Create product card HTML
  function createProductCard(product) {
    const isNew = isProductNew(product.createdAt);
    const hasDiscount = product.discount > 0;
    const discountedPrice = hasDiscount
      ? Math.round((product.price * (100 - product.discount)) / 100)
      : null;

    return `
            <div class="col s12 m6 l4">
                <div class="card product-card" data-id="${product.id}">
                    <div class="product-image-container">
                        <img src="${
                          API_BASE_URL +
                          "/api/v2/source/storage/" +
                          product.images[0]
                        }" alt="${product.name}" class="product-image">
                        ${
                          isNew
                            ? '<span class="product-badge badge-new">BARU</span>'
                            : ""
                        }
                        ${
                          hasDiscount
                            ? `<span class="product-badge badge-sale">${product.discount}% OFF</span>`
                            : ""
                        }
                    </div>
                    <div class="product-content">
                        <h5 class="product-title">${product.name}</h5>
                        <div class="rating" style="color: #ffab00; margin: 5px 0;">
                            ${generateStarRating(product.rating)}
                            <span style="color: #9e9e9e; font-size: 12px;">(${
                              product.reviewCount
                            })</span>
                        </div>
                        <div class="product-price">
                            ${
                              hasDiscount
                                ? `
                                <span class="current-price">Rp ${formatCurrency(
                                  discountedPrice
                                )}</span>
                                <span class="original-price">Rp ${formatCurrency(
                                  product.price
                                )}</span>
                            `
                                : `
                                <span class="current-price">Rp ${formatCurrency(
                                  product.price
                                )}</span>
                            `
                            }
                        </div>
                    </div>
                    <div class="product-actions">
                        <a href="#" class="blue-text view-detail">Detail</a>
                        <a href="#" class="green-text add-to-cart">+ Keranjang</a>
                    </div>
                </div>
            </div>
        `;
  }

  // Check if product is new (within 30 days)
  function isProductNew(createdAt) {
    const thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
    return new Date(createdAt) > thirtyDaysAgo;
  }

  // Generate star rating HTML
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

  // Format currency
  function formatCurrency(amount) {
    return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  // Show toast message
  function showToast(message, color = "green") {
    M.toast({ html: message, classes: color });
  }

  // Update cart UI
  function updateCartUI() {
    const itemCount = cart.reduce((total, item) => total + item.quantity, 0);

    // Update cart badge
    const cartBadge = $("#cart-count");
    cartBadge.text(itemCount);
    itemCount > 0 ? cartBadge.show() : cartBadge.hide();

    // Save to localStorage
    localStorage.setItem("cart", JSON.stringify(cart));
  }

  // Update cart modal
  function updateCartModal() {
    const cartItemsContainer = $("#cart-items");
    const cartSummary = $("#cart-summary");
    const emptyCartMessage = $("#empty-cart-message");
    const cartFooter = $("#cart-footer");

    // Kosongkan kontainer item keranjang
    cartItemsContainer.empty();

    if (cart.length === 0) {
      cartItemsContainer.hide();
      cartSummary.hide();
      cartFooter.hide();
      emptyCartMessage.show();
      return;
    }

    cartItemsContainer.show();
    cartSummary.show();
    cartFooter.show();
    emptyCartMessage.hide();

    let subtotal = 0;

    // Render setiap item di keranjang
    cart.forEach((item, index) => {
      const product = item.product;
      const price = product.discountedPrice || product.price;
      const itemTotal = price * item.quantity;
      subtotal += itemTotal;

      cartItemsContainer.append(`
            <div class="cart-item row valign-wrapper" data-index="${index}">
                <div class="col s3">
                    <img src="${
                      API_BASE_URL +
                      "/api/v2/source/storage/" +
                      product.images[0]
                    }" alt="${
        product.name
      }" class="responsive-img" style="max-height: 80px;">
                </div>
                <div class="col s5">
                    <h6 style="margin-top: 0;">${product.name}</h6>
                    <p>Rp ${formatCurrency(price)} x ${item.quantity}</p>
                </div>
                <div class="col s3 right-align">
                    <strong>Rp ${formatCurrency(itemTotal)}</strong>
                </div>
                <div class="col s1">
                    <a href="#" class="red-text remove-item"><i class="material-icons">delete</i></a>
                </div>
            </div>
            <div class="divider"></div>
        `);
    });

    // Hitung ongkos kirim dan total
    const shippingCost = 0; // Contoh flat rate
    const total = subtotal + shippingCost;

    // Update ringkasan keranjang
    $("#subtotal-amount").text(`Rp ${formatCurrency(subtotal)}`);
    // $("#shipping-cost").text(`Rp ${formatCurrency(shippingCost)}`);
    $("#total-amount").text(`Rp ${formatCurrency(total)}`);
  }

  // Add to cart
  function addToCart(product, quantity = 1) {
    const existingItem = cart.find((item) => item.product.id === product.id);

    if (existingItem) {
      // Check stock
      if (existingItem.quantity + quantity > product.stock) {
        showToast(
          `Stok tidak mencukupi. Stok tersisa: ${product.stock}`,
          "red"
        );
        return false;
      }
      existingItem.quantity += quantity;
    } else {
      cart.push({
        product: product,
        quantity: quantity,
      });
    }

    updateCartUI();
    showToast(`${product.name} ditambahkan ke keranjang`, "green");
    return true;
  }

  // Load more products when scrolling
  $(window).scroll(function () {
    if (
      $(window).scrollTop() + $(window).height() >
      $(document).height() - 300
    ) {
      if (!isLoading && hasMore) {
        loadMoreProducts();
      }
    }
  });

  // Load more products
  function loadMoreProducts() {
    isLoading = true;
    $("#loading-products").show();

    fetchProducts(currentPage + 1)
      .then((newProducts) => {
        if (newProducts.length > 0) {
          products = [...products, ...newProducts];
          renderProducts();
          currentPage++;
        } else {
          hasMore = false;
          $("#no-more-products").show();
        }
      })
      .catch((error) => {
        console.error("Error loading more products:", error);
        showToast("Gagal memuat produk tambahan", "red");
      })
      .finally(() => {
        isLoading = false;
        $("#loading-products").hide();
      });
  }

  // View product detail
  $(document).on("click", ".view-detail", function (e) {
    e.preventDefault();
    const productId = $(this).closest(".product-card").data("id");
    currentProduct = products.find((p) => p.id === productId);

    if (currentProduct) {
      showProductModal(currentProduct);
    }
  });

  // Show product modal
  function showProductModal(product) {
    // Set basic info
    $("#modal-product-name").text(product.name);
    $("#product-sku").text(`SKU: ${product.sku}`);
    $("#modal-product-description").html(product.description);

    // Set rating
    $("#product-rating").html(generateStarRating(product.rating));
    $("#review-count").text(`(${product.reviewCount} ulasan)`);

    // Set price
    if (product.discount > 0) {
      const discountedPrice = Math.round(
        (product.price * (100 - product.discount)) / 100
      );
      $("#product-price").html(`Rp ${formatCurrency(discountedPrice)}`);
      $("#discount-info").html(`
                <span class="original-price">Rp ${formatCurrency(
                  product.price
                )}</span>
                <span class="badge-sale" style="margin-left: 10px;">${
                  product.discount
                }% OFF</span>
            `);
    } else {
      $("#product-price").html(`Rp ${formatCurrency(product.price)}`);
      $("#discount-info").empty();
    }

    // Set images
    const imagesContainer = $("#product-images");
    const thumbnailsContainer = $("#product-thumbnails");
    imagesContainer.empty();
    thumbnailsContainer.empty();

    product.images.forEach((image, index) => {
      const imageUrl = API_BASE_URL + "/api/v2/source/storage/" + image;
      imagesContainer.append(`
                <div class="slider-item">
                    <img src="${imageUrl}" alt="${product.name}" class="responsive-img">
                </div>
            `);
      thumbnailsContainer.append(`
                <img src="${imageUrl}" alt="${product.name}" class="thumbnail"  
                      style="width: 60px; height: 100%; cursor: pointer; margin-right: 5px;">
            `);
      if (index === 0) {
        thumbnailsContainer
          .find(".thumbnail")
          .css("border", "2px solid #2196F3");
      }
    });

    // Initialize slider
    M.Slider.init(document.querySelector(".slider"), {
      indicators: false,
      height: 400,
    });

    // Set stock info
    $("#stock-info").html(`
            <span class="${product.stock > 0 ? "green-text" : "red-text"}">
                <i class="material-icons tiny">${
                  product.stock > 0 ? "check_circle" : "cancel"
                }</i>
                ${product.stock > 0 ? "Stok Tersedia" : "Stok Habis"}
            </span>
            ${
              product.stock > 0
                ? `<span class="grey-text" style="margin-left: 10px;">${product.stock} tersisa</span>`
                : ""
            }
        `);

    // Set quantity max
    $("#product-quantity").attr("max", product.stock).val(1);

    // Open modal
    productModal.open();
  }

  // Add to cart from product card
  $(document).on("click", ".add-to-cart", function (e) {
    e.preventDefault();
    const productId = $(this).closest(".product-card").data("id");
    const product = products.find((p) => p.id === productId);

    if (product) {
      if (addToCart(product, 1)) {
        // Optional: Show a brief animation
        $(this).html('<i class="material-icons tiny">check</i> Ditambahkan');
        setTimeout(() => {
          $(this).html("+ Keranjang");
        }, 1000);
      }
    }
  });

  // Add to cart from modal
  $("#add-to-cart-btn").click(function () {
    const quantity = parseInt($("#product-quantity").val());

    if (!currentProduct || quantity < 1 || quantity > currentProduct.stock) {
      showToast("Jumlah tidak valid", "red");
      return;
    }

    if (addToCart(currentProduct, quantity)) {
      productModal.close();
      cartModal.open();
    }
  });

  // Buy now button
  // Buy now button
  $("#buy-now-btn").click(function () {
    const quantity = parseInt($("#product-quantity").val());

    if (!currentProduct || quantity < 1 || quantity > currentProduct.stock) {
      showToast("Jumlah tidak valid", "red");
      return;
    }

    // Replace cart with this single item
    cart = [
      {
        product: currentProduct,
        quantity: quantity,
      },
    ];

    updateCartUI();
    productModal.close();

    // Update checkout UI before opening the modal
    updateCheckoutUI();
    checkoutModal.open();
  });

  // Remove item from cart
  $(document).on("click", ".remove-item", function (e) {
    e.preventDefault();
    const index = $(this).closest(".cart-item").data("index");
    const productName = cart[index].product.name;

    cart.splice(index, 1);
    updateCartUI();
    updateCartModal();

    showToast(`${productName} dihapus dari keranjang`, "orange");
  });

  // Category filter
  $(document).on("click", ".category-chip", function () {
    selectedCategory = $(this).data("category");
    $(".category-chip").removeClass("active");
    $(this).addClass("active");

    // Reset to first page when changing category
    currentPage = 1;
    hasMore = true;
    $("#no-more-products").hide();

    renderProducts();
  });

  // Sort products
  $("#sort-products").change(function () {
    currentSort = $(this).val();

    // Reset to first page when changing sort
    currentPage = 1;
    hasMore = true;
    $("#no-more-products").hide();

    renderProducts();
  });

  // Product search
  $("#search-product").on(
    "input",
    debounce(function () {
      currentSearch = $(this).val();

      // Reset to first page when searching
      currentPage = 1;
      hasMore = true;
      $("#no-more-products").hide();

      renderProducts();
    }, 300)
  );

  // Debounce function
  function debounce(func, wait) {
    let timeout;
    return function () {
      const context = this;
      const args = arguments;
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(context, args), wait);
    };
  }

  // Update checkout UI
  // Update checkout UI
  function updateCheckoutUI() {
    const orderSummary = $("#order-summary");
    orderSummary.empty();

    let subtotal = 0;
    let itemCount = 0;

    if (cart.length === 0) {
      orderSummary.append(`
      <div class="center-align" style="padding: 20px;">
        <p>Keranjang belanja kosong</p>
      </div>
    `);
      return;
    }

    cart.forEach((item) => {
      const price = item.product.discountedPrice || item.product.price;
      const itemTotal = price * item.quantity;
      subtotal += itemTotal;
      itemCount += item.quantity;

      orderSummary.append(`
      <div class="row" style="margin-bottom: 10px;">
        <div class="col s8">
          <p>${item.product.name} (x${item.quantity})</p>
        </div>
        <div class="col s4 right-align">
          <p>Rp ${formatCurrency(itemTotal)}</p>
        </div>
      </div>
      <div class="divider" style="margin: 5px 0;"></div>
    `);
    });

    // Calculate shipping (simplified)
    const shippingCost = 15000;
    const total = subtotal + shippingCost;

    // Update totals
    $("#checkout-total").text(`Rp ${formatCurrency(total)}`);
  }
  // Update your checkout form submission
  $("#checkout-form").submit(function (e) {
    e.preventDefault();

    // Validate form
    const name = $("#customer-name").val().trim();
    const email = $("#customer-email").val().trim();
    const phone = $("#customer-phone").val().trim();
    const address = $("#shipping-address").val().trim();
    const notes = $("#order-notes").val().trim();

    const paymentMethod = $('input[name="payment-method"]:checked').val();
    const midtransMethod = $("#midtrans-method").val();
    const agreeTerms = $("#agree-terms").is(":checked");

    if (!name || !email || !phone || !address || !paymentMethod) {
      showToast("Harap lengkapi semua field yang wajib diisi", "red");
      return;
    }

    if (!agreeTerms) {
      showToast("Anda harus menyetujui syarat dan ketentuan", "red");
      return;
    }

    if (cart.length === 0) {
      showToast("Keranjang belanja kosong", "red");
      return;
    }

    // Calculate totals
    const subtotal = cart.reduce((sum, item) => {
      const price = item.product.discountedPrice || item.product.price;
      return sum + price * item.quantity;
    }, 0);

    // Add shipping cost (COD has additional fee)
    const shippingCost = paymentMethod === "cod" ? 10000 : 0;
    const total = subtotal + shippingCost;

    // Generate order number
    const orderNumber = "ORD-" + Date.now().toString(36).toUpperCase();

    // Prepare order data
    const orderData = {
      orderNumber: orderNumber,
      customerName: name,
      customerEmail: email,
      customerPhone: phone,
      shippingAddress: address,
      notes: notes,
      paymentMethod: paymentMethod,
      midtransMethod: midtransMethod,
      items: cart.map((item) => ({
        productId: item.product.id,
        productName: item.product.name,
        quantity: item.quantity,
        price: item.product.discountedPrice || item.product.price,
      })),
      subtotal: subtotal,
      shippingCost: shippingCost,
      total: total,
      status: paymentMethod === "cod" ? "pending" : "waiting_payment",
    };

    // Show loading
    $(".preloader").show();

    // Handle different payment methods
    if (paymentMethod === "midtrans") {
      // Process via Midtrans
      processMidtransPayment(orderData);
    } else {
      // Process COD or Bank Transfer normally
      processRegularPayment(orderData);
    }
  });

  // Function to process regular payments (COD/Bank Transfer)
  function processRegularPayment(orderData) {
    $.ajax({
      url: `${API_BASE_URL}/api/transaksi/save`,
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify(orderData),
      success: function (response) {
        $(".preloader").hide();

        // Clear cart
        cart = [];
        updateCartUI();

        // Update confirmation modal
        $("#order-number").text(orderData.orderNumber);
        $("#order-total").text(`Rp ${formatCurrency(orderData.total)}`);

        // Close modals and show confirmation
        checkoutModal.close();
        orderConfirmModal.open();
      },
      error: function (xhr, status, error) {
        $(".preloader").hide();
        console.error("Error saving order:", error);
        showToast("Gagal membuat pesanan", "red");
      },
    });
  }

  // Function to process Midtrans payment
  function processMidtransPayment(orderData) {
    // First save the order to your database
    $.ajax({
      url: `${API_BASE_URL}/api/transaksi/save`,
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify(orderData),
      success: function (response) {
        // Then create Midtrans transaction
        const transactionDetails = {
          transaction_details: {
            order_id: orderData.orderNumber,
            gross_amount: orderData.total,
          },
          customer_details: {
            first_name: orderData.customerName,
            email: orderData.customerEmail,
            phone: orderData.customerPhone,
          },
          item_details: orderData.items.map((item) => ({
            id: item.productId,
            price: item.price,
            quantity: item.quantity,
            name: item.productName,
          })),
          enabled_payments: [orderData.midtransMethod],
        };

        // Get Snap token from your server
        $.ajax({
          url: `${API_BASE_URL}/api/midtrans/token`,
          method: "POST",
          contentType: "application/json",
          data: JSON.stringify(transactionDetails),
          success: function (response) {
            $(".preloader").hide();

            // Clear cart
            cart = [];
            updateCartUI();

            // Open Midtrans payment popup
            snap.pay(response.token, {
              onSuccess: function (result) {
                console.log("Payment success:", result);
                // Update order status to paid
                updateOrderStatus(orderData.orderNumber, "paid");

                // Show confirmation modal
                $("#order-number").text(orderData.orderNumber);
                $("#order-total").text(`Rp ${formatCurrency(orderData.total)}`);
                orderConfirmModal.open();
              },
              onPending: function (result) {
                console.log("Payment pending:", result);
                updateOrderStatus(orderData.orderNumber, "pending");
                showToast("Pembayaran masih pending", "orange");
              },
              onError: function (result) {
                console.error("Payment error:", result);
                showToast("Gagal memproses pembayaran", "red");
              },
            });
          },
          error: function (xhr, status, error) {
            $(".preloader").hide();
            console.error("Error getting Snap token:", error);
            showToast("Gagal memproses pembayaran", "red");
          },
        });
      },
      error: function (xhr, status, error) {
        $(".preloader").hide();
        console.error("Error saving order:", error);
        showToast("Gagal membuat pesanan", "red");
      },
    });
  }

  // Function to update order status
  function updateOrderStatus(orderNumber, status) {
    $.ajax({
      url: `${API_BASE_URL}/api/transaksi/update-status`,
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        orderNumber: orderNumber,
        status: status,
      }),
      success: function (response) {
        console.log("Order status updated:", response);
      },
      error: function (xhr, status, error) {
        console.error("Error updating order status:", error);
      },
    });
  }

  // Payment method change
  $(document).on("change", 'input[name="payment-method"]', function () {
    $(".payment-details").hide();
    $(`#${$(this).val()}-details`).show();
  });

  // Thumbnail click
  $(document).on("click", ".thumbnail", function () {
    const index = $(this).index();
    $(".thumbnail").css("border", "1px solid #eee");
    $(this).css("border", "2px solid #2196F3");
    $(".slider").slider("set", index);
  });

  // Star rating in review
  $(document).on("mouseover", ".star", function () {
    const value = $(this).data("value");
    highlightStars(value);
  });

  $(document).on("click", ".star", function () {
    const value = $(this).data("value");
    $("#star-rating").data("rating", value);
    highlightStars(value);
  });

  function highlightStars(count) {
    $(".star").each(function (i) {
      if (i < count) {
        $(this).text("star");
      } else {
        $(this).text("star_border");
      }
    });
  }

  // Submit review
  $("#submit-review").click(function () {
    const rating = $("#star-rating").data("rating");
    const name = $("#reviewer-name").val().trim();
    const text = $("#review-text").val().trim();

    if (!rating) {
      showToast("Harap beri rating", "red");
      return;
    }

    if (!name || !text) {
      showToast("Harap isi nama dan ulasan", "red");
      return;
    }

    // In a real app, this would be sent to the server
    const review = {
      name: name,
      rating: rating,
      text: text,
      date: new Date().toLocaleDateString("id-ID"),
    };

    // Add to reviews section
    $("#product-reviews").prepend(`
            <div class="review" style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                <div style="font-weight: bold;">${review.name}</div>
                <div style="color: #ffab00; margin: 5px 0;">${generateStarRating(
                  review.rating
                )}</div>
                <div>${review.text}</div>
                <div style="color: #9e9e9e; font-size: 12px; margin-top: 5px;">${
                  review.date
                }</div>
            </div>
        `);

    // Clear form
    $("#reviewer-name").val("");
    $("#review-text").val("");
    highlightStars(0);
    $("#star-rating").removeData("rating");

    showToast("Ulasan berhasil dikirim", "green");
  });

  // Initialize the app
  loadInitialData();
});
