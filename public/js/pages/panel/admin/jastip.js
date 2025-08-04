const table = {
  jastip: $("#table-jastip").DataTable({
    responsive: true,
    ajax: {
      url: origin + "/api/jastip",
      dataSrc: "",
    },
    order: [[5, "desc"]],
    columns: [
      {
        title: "#",
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        },
      },
      {
        title: "Pemesan",
        data: "user",
        render: function (data) {
          return data ? data.name : "Unknown";
        },
      },
      { title: "Keterangan", data: "keterangan" },
      {
        title: "Jumlah Item",
        data: "details",
        render: function (data) {
          return data ? data.length : 0;
        },
      },
      {
        title: "Status",
        data: "status",
        render: function (data) {
          let badgeClass = "grey";
          if (data === "diproses") badgeClass = "blue";
          if (data === "selesai") badgeClass = "green";
          if (data === "dibatalkan") badgeClass = "red";

          return `<span class="badge ${badgeClass}">${data}</span>`;
        },
      },
      {
        title: "Tanggal",
        data: "created_at",
        render: function (data) {
          return new Date(data).toLocaleDateString();
        },
      },
      {
        title: "Aksi",
        data: "id",
        render: (data, type, row) => {
          return `<div class="table-control">
            <a role="button" class="btn waves-effect waves-light btn-action btn-popup blue" data-action="detail" data-id="${data}"><i class="material-icons">info</i></a>
            <a role="button" class="btn waves-effect waves-light btn-action btn-popup orange darken-2" data-target="edit" data-action="edit" data-id="${data}"><i class="material-icons">edit</i></a>
            <a role="button" class="btn waves-effect waves-light btn-action red" data-action="delete" data-id="${data}"><i class="material-icons">delete</i></a>
          </div>`;
        },
      },
    ],
  }),
};

// Counter untuk item baru
let itemCounter = 1;

// Fungsi untuk menambahkan item baru ke form
function addItemForm(containerId, index = null, itemData = null) {
  const container = $(`#${containerId}`);
  const newIndex = index !== null ? index : itemCounter++;

  const itemHtml = `
    <div class="item-row row" data-index="${newIndex}">
      <input type="hidden" name="items[${newIndex}][id]" value="${
    itemData ? itemData.id : ""
  }">
      <div class="input-field col s5">
        <input type="text" name="items[${newIndex}][nama_barang]" class="validate" value="${
    itemData ? itemData.nama_barang : ""
  }" required>
        <label>Nama Barang</label>
      </div>
      <div class="input-field col s2">
        <input type="number" name="items[${newIndex}][jumlah]" class="validate" value="${
    itemData ? itemData.jumlah : ""
  }" required min="1">
        <label>Jumlah</label>
      </div>
      <div class="input-field col s3">
        <input type="text" name="items[${newIndex}][keterangan]" class="validate" value="${
    itemData ? itemData.keterangan : ""
  }">
        <label>Keterangan</label>
      </div>
     
      <div class="col s1">
        <button type="button" class="btn-floating red remove-item"><i class="material-icons">remove</i></button>
      </div>
    </div>
  `;

  container.append(itemHtml);

  // Inisialisasi materialize select
  M.FormSelect.init(container.find("select").last());

  // Inisialisasi materialize input fields
  M.updateTextFields();
}

// Tambah item baru ke form add
$("#add-item").click(function () {
  addItemForm("items-container");
});

// Tambah item baru ke form edit
$("#edit-add-item").click(function () {
  addItemForm("edit-items-container");
});

// Hapus item
$(document).on("click", ".remove-item", function () {
  $(this).closest(".item-row").remove();
});

// Submit form tambah jastip
$("form#form-add").on("submit", function (e) {
  e.preventDefault();

  const form = this;
  const formData = new FormData(form);

  const elements = form.elements;
  for (let i = 0, len = elements.length; i < len; ++i) {
    elements[i].readOnly = true;
  }
  // console log isi dari formData;
  for (const [key, value] of formData.entries()) {
    console.log(`${key}: ${value}`);
  }
  $.ajax({
    type: "POST",
    url: origin + "/api/jastip",
    data: formData,
    contentType: false,
    processData: false,
    success: (data) => {
      form.reset();
      table.jastip.ajax.reload();
      if (data.messages) {
        $.each(data.messages, function (icon, text) {
          Toast.fire({
            icon: icon,
            title: text,
          });
        });
      }
      $(".btn-popup-close").trigger("click");
    },
    complete: () => {
      for (let i = 0, len = elements.length; i < len; ++i) {
        elements[i].readOnly = false;
      }
    },
    error: (xhr) => {
      const errors = xhr.responseJSON?.messages?.errors || {};
      for (const field in errors) {
        Toast.fire({
          icon: "error",
          title: errors[field],
        });
      }
    },
  });
});

// Submit form edit jastip
$("form#form-edit").on("submit", function (e) {
  e.preventDefault();

  const form = this;
  const formData = new FormData(form);
  const id = $("#edit-id").val();

  const elements = form.elements;
  for (let i = 0, len = elements.length; i < len; ++i) {
    elements[i].readOnly = true;
  }

  $.ajax({
    type: "POST",
    url: origin + "/api/jastip/" + id,
    data: formData,
    contentType: false,
    processData: false,
    success: (data) => {
      form.reset();
      table.jastip.ajax.reload();
      if (data.messages) {
        $.each(data.messages, function (icon, text) {
          Toast.fire({
            icon: icon,
            title: text,
          });
        });
      }
      $(".btn-popup-close").trigger("click");
    },
    complete: () => {
      for (let i = 0, len = elements.length; i < len; ++i) {
        elements[i].readOnly = false;
      }
    },
    error: (xhr) => {
      const errors = xhr.responseJSON?.messages?.errors || {};
      for (const field in errors) {
        Toast.fire({
          icon: "error",
          title: errors[field],
        });
      }
    },
  });
});

// Handle action buttons
$("body").on("click", ".btn-action", function (e) {
  e.preventDefault();
  const action = $(this).data("action");
  const id = $(this).data("id");

  switch (action) {
    case "delete":
      Swal.fire({
        title: "Apakah anda yakin ingin menghapus data ini?",
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
            url: origin + "/api/jastip/" + id,
            cache: false,
            success: (data) => {
              table.jastip.ajax.reload();
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
      $.ajax({
        url: origin + "/api/jastip/" + id,
        success: (data) => {
          $("form#form-edit")[0].reset();
          $("#edit-id").val(data.id);
          $("#edit-keterangan").val(data.keterangan);
          M.textareaAutoResize($("#edit-keterangan"));

          // Kosongkan container items
          $("#edit-items-container").empty();

          // Tambahkan items ke form edit
          if (data.details && data.details.length > 0) {
            data.details.forEach((item, index) => {
              addItemForm("edit-items-container", index, item);
            });
          } else {
            addItemForm("edit-items-container");
          }

          M.updateTextFields();
        },
      });
      break;

    case "detail":
      console.log("Detail action for ID:", id);
      $.ajax({
        url: origin + "/api/jastip/" + id,
        success: (data) => {
          $("#detail-keterangan").text(data.keterangan || "-");
          $("#detail-status").text(data.status || "-");
          $("#detail-catatan").text(data.catatan || "-");
          $("#detail-tanggal").text(new Date(data.created_at).toLocaleString());

          // Kosongkan dan isi daftar barang
          $("#detail-items").empty();
          if (data.details && data.details.length > 0) {
            data.details.forEach((item) => {
              $("#detail-items").append(`
                <tr>
                  <td>${item.nama_barang}</td>
                  <td>${item.jumlah}</td>
                  <td>${item.keterangan || "-"}</td>
                  <td>
                    <span class="badge ${
                      item.status === "diproses"
                        ? "blue"
                        : item.status === "selesai"
                        ? "green"
                        : item.status === "dibatalkan"
                        ? "red"
                        : "grey"
                    }">${item.status}</span>
                  </td>
                  <td>${item.catatan || "-"}</td>
                </tr>
              `);
            });
          } else {
            $("#detail-items").append(
              '<tr><td colspan="5" class="center">Tidak ada barang</td></tr>'
            );
          }

          // Buka popup detail
          $('.popup[data-page="detail"]').addClass("active");
        },
      });
      break;
  }
});

$(document).ready(function () {
  // Inisialisasi select
  M.FormSelect.init(document.querySelectorAll("select"));

  // Inisialisasi textarea
  M.textareaAutoResize($("textarea"));

  // Load data awal
  table.jastip.ajax.reload();
  $(".preloader").slideUp();
});
