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
      { title: "User", data: "user.username" },
      { title: "Total Harga", data: "total_harga" },
      { title: "Status", data: "status" },
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
          <a role="button" class="btn waves-effect waves-light btn-action btn-popup orange darken-2" data-target="edit" data-action="edit" data-id="${data}"><i class="material-icons">edit</i></a>
          <a role="button" class="btn waves-effect waves-light btn-action red" data-action="delete" data-id="${data}"><i class="material-icons">delete</i></a>
          </div>`;
        },
      },
    ],
  }),
};

$("form#form-add").on("submit", function (e) {
  e.preventDefault();

  const form = this;
  const formData = new FormData(form); // Ini akan otomatis menangkap file juga

  const elements = form.elements;
  for (let i = 0, len = elements.length; i < len; ++i) {
    elements[i].readOnly = true;
  }

  console.log(formData);

  $.ajax({
    type: "POST",
    url: origin + "/api/transaksi",
    data: formData,
    contentType: false, // WAJIB agar FormData bekerja
    processData: false, // WAJIB agar FormData tidak diubah jadi query string
    success: (data) => {
      form.reset();
      cloud.pull("transaksi");
      if (data.messages) {
        $.each(data.messages, function (icon, text) {
          Toast.fire({
            icon: icon,
            title: text,
          });
        });
      }
    },
    complete: () => {
      for (let i = 0, len = elements.length; i < len; ++i) {
        elements[i].readOnly = false;
      }
    },
  });
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

      // Isi semua field kecuali input file
      $.each(dataEdit, function (field, val) {
        const input = $("form#form-edit").find(`[name=${field}]`);
        if (input.is("select")) {
          input.val(val).trigger("change");
        } else if (!input.is('input[type="file"]')) {
          input.val(val);
        }
      });

      // Tambahkan preview gambar yang sudah ada
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
    default:
      break;
  }
});

$("form#form-edit").on("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(this); // Gunakan FormData untuk menangani file juga

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

// Tambahkan event ketika popup ditutup
$("body").on("click", ".btn-popup-close", function () {
  $(".image-preview").remove(); // Hapus preview gambar
});

$(document).ready(function () {
  cloud
    .add(origin + "/api/transaksi", {
      name: "transaksi",
      callback: (data) => {
        table.transaksi.ajax.reload();
      },
    })
    .then((transaksi) => {});
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
