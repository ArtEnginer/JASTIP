const table = {
  transaksi: $("#table-transaksi").DataTable({
    responsive: true,
    ajax: {
      url: origin + "/api/transaksi",
      dataSrc: "",
    },
    order: [
      [0, "asc"],
      [3, "asc"],
    ],
    columns: [
      {
        title: "#",
        data: "id",
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        },
      },
      {
        title: "Kode Transaksi",
        data: "order_id",
        render: (data, type, row) => {
          return `<a href="#" class="btn-action" data-action="view" data-id="${row.id}">${data}</a>`;
        },
      },
      { title: "User", data: "user.username" },
      {
        title: "Total Harga",
        data: "total_harga",
        render: (data) => {
          return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
          }).format(data);
        },
      },
      {
        title: "Status",
        data: "status",
        render: (data) => {
          switch (data) {
            case "pending":
              return `<span class="pl-2 pr-2 rounded text-white orange darken-2" >${data}</span>`;
            case "dikemas":
              return `<span class="pl-2 pr-2 rounded text-white green" >${data}</span>`;
            case "dikirim":
              return `<span class="pl-2 pr-2 rounded text-white red" >${data}</span>`;
            case "selesai":
              return `<span class="pl-2 pr-2 rounded text-white blue" >${data}</span>`;
            case "dibatalkan":
              return `<span class="pl-2 pr-2 rounded text-white grey darken-2" >${data}</span>`;
            default:
              return `<span class="pl-2 pr-2 rounded text-white grey">${data}</span>`;
          }
        },
      },
      { title: "Nama Penerima", data: "nama_penerima" },
      { title: "Alamat Pengiriman", data: "alamat_penerima" },
      { title: "Email Penerima", data: "email_penerima" },
      {
        title: "Tanggal",
        data: "created_at",
        render: (data) => new Date(data).toLocaleDateString(),
      },
      {
        title: "Aksi",
        data: "id",
        render: (data, type, row) => {
          return `<div class="table-control">
            <a role="button" class="btn waves-effect waves-light btn-action blue" data-action="view" data-id="${data}">
                <i class="material-icons">visibility</i>
            </a>
            <a role="button" class="btn waves-effect waves-light btn-action orange darken-2" data-action="edit-status" data-id="${data}">
                <i class="material-icons">swap_vert</i>
            </a>
            <a role="button" class="btn waves-effect waves-light btn-action red" data-action="delete" data-id="${data}">
                <i class="material-icons">delete</i>
            </a>
        </div>`;
        },
      },
    ],
  }),
};

const modalEditStatus = M.Modal.init(
  document.querySelector("#modal-edit-status"),
  {
    onCloseEnd: () => {
      $("#detail-produk-list").empty();
    },
  }
);

$("body").on("click", ".btn-action[data-action='edit-status']", function (e) {
  e.preventDefault();
  const id = $(this).data("id");
  const transaksi = cloud.get("transaksi").find((x) => x.id == id);
  $("form#form-edit-status input[name=id]").val(transaksi.id);
  $("form#form-edit-status select[name=status]").val(transaksi.status);
  M.FormSelect.init($("form#form-edit-status select"));
  modalEditStatus.open();
});

$("form#form-edit-status").on("submit", function (e) {
  e.preventDefault();
  const form = $(this);
  const id = form.find("input[name=id]").val();
  const status = form.find("select[name=status]").val();
  console.log("Updating status for ID:", id, "to status:", status);
  $.ajax({
    type: "POST",
    url: origin + "/api/transaksi/update-status",
    data: { id: id, status: status },
    success: (data) => {
      modalEditStatus.close();
      table.transaksi.ajax.reload();
      if (data.messages) {
        $.each(data.messages, function (icon, text) {
          Toast.fire({
            icon: icon,
            title: text,
          });
        });
      }
      cloud.pull("transaksi");
    },
    error: (xhr) => {
      Toast.fire({
        icon: "error",
        title: xhr.responseJSON?.messages?.error || "Gagal mengupdate status",
      });
    },
  });
});

// Initialize the modal with proper options
const modalDetailTransaksi = M.Modal.init(
  document.querySelector("#modal-detail-transaksi"),
  {
    onCloseEnd: () => {
      $("#detail-produk-list").empty();
    },
  }
);

// Add this event listener for the close button
$("body").on("click", ".btn-popup-close", function (e) {
  e.preventDefault();
  modalDetailTransaksi.close();
  modalEditStatus.close();
});

$("body").on("click", ".btn-action", function (e) {
  e.preventDefault();
  const action = $(this).data("action");
  const id = $(this).data("id");
  switch (action) {
    case "delete":
      Swal.fire({
        title: "Apakah anda yakin ingin menghapus data ini ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Hapus",
        cancelButtonText: "Batal",
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: "DELETE",
            url: origin + "/api/transaksi/" + id,
            cache: false,
            success: (data) => {
              table.transaksi.ajax.reload();
              if (data.messages) {
                $.each(data.messages, function (icon, text) {
                  Toast.fire({
                    icon: icon,
                    title: text,
                  });
                });
              }
            },
          });
        }
      });
      break;
    case "edit":
      let dataEdit = cloud.get("transaksi").find((x) => x.id == id);
      $("form#form-edit")[0].reset();
      $("form#form-edit").find("input[name=id]").val(dataEdit.id);

      $.each(dataEdit, function (field, val) {
        const input = $("form#form-edit").find(`[name=${field}]`);
        if (input.is("select")) {
          input.val(val).trigger("change");
        } else if (!input.is('input[type="file"]')) {
          input.val(val);
        }
      });

      if (dataEdit.gambar) {
        const previewContainer = $(
          '<div class="image-preview"><img src="' +
            origin +
            "/api/v2/source/storage/" +
            dataEdit.gambar +
            '" style="max-height: 150px; margin-bottom: 10px;"><p>Gambar saat ini</p></div>'
        );
        $("form#form-edit")
          .find('input[name="gambar"]')
          .before(previewContainer);
      }

      M.updateTextFields();
      M.textareaAutoResize($("textarea"));
      break;
    // In your switch case for button actions, add:
    case "view":
      const transaksi = cloud.get("transaksi").find((x) => x.id == id);
      const detailTransaksi = cloud
        .get("detailTransaksi")
        .filter((x) => x.transaksi_id == id);

      // Set basic info
      $("#detail-transaksi-id").text(transaksi.order_id);
      $("#detail-nama-penerima").text(transaksi.nama_penerima);
      $("#detail-alamat").text(transaksi.alamat_penerima);
      $("#detail-email").text(transaksi.email_penerima);
      $("#detail-telepon").text(transaksi.nomor_telepon_penerima);
      $("#detail-total-harga").text(
        new Intl.NumberFormat("id-ID", {
          style: "currency",
          currency: "IDR",
        }).format(transaksi.total_harga)
      );
      $("#detail-tanggal").text(
        new Date(transaksi.created_at).toLocaleString()
      );

      // Set status with appropriate color
      const statusElement = $("#detail-status");
      statusElement.text(transaksi.status);
      statusElement.removeClass().addClass("pl-2 pr-2 rounded text-white");

      switch (transaksi.status) {
        case "pending":
          statusElement.addClass("orange darken-2");
          break;
        case "dikemas":
          statusElement.addClass("green");
          break;
        case "dikirim":
          statusElement.addClass("red");
          break;
        case "selesai":
          statusElement.addClass("blue");
          break;
        case "dibatalkan":
          statusElement.addClass("grey darken-2");
          break;
        default:
          statusElement.addClass("grey");
      }

      // Populate products list
      const produkList = $("#detail-produk-list");
      produkList.empty();

      detailTransaksi.forEach((item, index) => {
        const subtotal = item.jumlah * parseFloat(item.harga);
        produkList.append(`
            <tr>
                <td>${index + 1}</td>
                <td>${item.produk.nama}</td>
                <td>${new Intl.NumberFormat("id-ID", {
                  style: "currency",
                  currency: "IDR",
                }).format(item.harga)}</td>
                <td>${item.jumlah}</td>
                <td>${new Intl.NumberFormat("id-ID", {
                  style: "currency",
                  currency: "IDR",
                }).format(subtotal)}</td>
            </tr>
        `);
      });

      modalDetailTransaksi.open();
      break;
    default:
      break;
  }
});

$("form#form-edit").on("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(this);

  const form = $(this)[0];
  const elements = form.elements;
  for (let i = 0, len = elements.length; i < len; ++i) {
    elements[i].readOnly = true;
  }

  $.ajax({
    type: "POST",
    url: origin + "/api/transaksi/" + $("input[name=id]", this).val(),
    data: formData,
    contentType: false,
    processData: false,
    success: (data) => {
      $(this)[0].reset();
      cloud.pull("transaksi");
      if (data.messages) {
        $.each(data.messages, function (icon, text) {
          Toast.fire({
            icon: icon,
            title: text,
          });
        });
      }
      $(this).closest(".popup").find(".btn-popup-close").trigger("click");
    },
    complete: () => {
      for (let i = 0, len = elements.length; i < len; ++i) {
        elements[i].readOnly = false;
      }
    },
  });
});

$("body").on("keyup", "#form-add input[name=nama]", function (e) {
  $("#form-add input[name=name]").val($(this).val());
});
$("body").on("keyup", "#form-edit input[name=nama]", function (e) {
  $("#form-edit input[name=name]").val($(this).val());
});

$("body").on("click", ".btn-popup-close", function () {
  $(".image-preview").remove();
});

$(document).ready(function () {
  cloud
    .add(origin + "/api/transaksi", {
      name: "transaksi",
      callback: (data) => {
        table.transaksi.ajax.reload();
      },
    })
    .then((transaksi) => {
      console.log("Transaksi data loaded:", transaksi);
    });
  cloud
    .add(origin + "/api/detail-transaksi", {
      name: "detailTransaksi",
      callback: (data) => {},
    })
    .then((detailTransaksi) => {
      console.log("Detail Transaksi data loaded:", detailTransaksi);
    });
  cloud
    .add(origin + "/api/kategori", {
      name: "kategori",
      callback: (data) => {
        M.FormSelect.init(document.querySelectorAll("select"));
        table.transaksi.ajax.reload();
      },
    })
    .then((transaksi) => {});

  $(".preloader").slideUp();
});
