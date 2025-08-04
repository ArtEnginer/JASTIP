const table = {
  jastip: $("#table-jastip").DataTable({
    responsive: true,
    ajax: {
      url: origin + "/api/jastip",
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
      { title: "Nama", data: "nama" },
      { title: "Kode", data: "kode" },
      { title: "Deskripsi", data: "deskripsi" },
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

  $.ajax({
    type: "POST",
    url: origin + "/api/jastip",
    data: formData,
    contentType: false, // WAJIB agar FormData bekerja
    processData: false, // WAJIB agar FormData tidak diubah jadi query string
    success: (data) => {
      form.reset();
      cloud.pull("jastip");
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
      let dataEdit = cloud.get("jastip").find((x) => x.id == id);
      console.log(dataEdit);
      $("form#form-edit")[0].reset();
      $("form#form-edit").find("input[name=id]").val(dataEdit.id);
      $.each(dataEdit, function (field, val) {
        $("form#form-edit").find(`[name=${field}]`).val(val);
      });
      M.updateTextFields();
      M.textareaAutoResize($("textarea"));
      M.FormSelect.init(document.querySelectorAll("select"));
      break;
    default:
      break;
  }
});

$("form#form-edit").on("submit", function (e) {
  e.preventDefault();
  const data = {};
  $(this)
    .serializeArray()
    .map(function (x) {
      data[x.name] = x.value;
    });

  const form = $(this)[0];
  const elements = form.elements;
  for (let i = 0, len = elements.length; i < len; ++i) {
    elements[i].readOnly = true;
  }

  $.ajax({
    type: "POST",
    url: origin + "/api/jastip/" + data.id,
    data: data,
    cache: false,
    success: (data) => {
      $(this)[0].reset();
      cloud.pull("jastip");
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

$(document).ready(function () {
  cloud
    .add(origin + "/api/jastip", {
      name: "jastip",
      callback: (data) => {
        table.jastip.ajax.reload();
      },
    })
    .then((jastip) => {
      console.log("jastip data loaded", jastip);
    });
  $(".preloader").slideUp();
});
