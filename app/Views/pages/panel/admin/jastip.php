<?= $this->extend('layouts/panel/main') ?>
<?= $this->section('main') ?>

<h1 class="page-title">Data Jasa Titip</h1>
<div class="page-wrapper">
    <div class="page">
        <div class="container">
            <div class="row">
                <div class="col-12 text-end">
                    <button class="btn waves-effect waves-light green btn-popup rounded" data-target="add" type="button"><i class="material-icons left">add</i>Tambah Jastip</button>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <div class="table-wrapper">
                        <table class="striped highlight responsive-table" id="table-jastip" width="100%">
                            <thead>

                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('popup') ?>
<!-- Form Tambah Jastip -->
<div class="popup side" data-page="add">
    <h1>Tambah Jasa Titip</h1>
    <br>
    <form id="form-add" class="row" enctype="multipart/form-data">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

        <!-- Informasi Pengiriman -->
        <div class="col s12">
            <h5>Informasi Pengiriman</h5>
        </div>

        <div class="input-field col s12 m6">
            <input type="text" name="nomor_resi" id="add-nomor_resi" required>
            <label for="add-nomor_resi">Nomor Resi</label>
        </div>

        <div class="input-field col s12 m6">
            <input type="text" name="nama_penerima" id="add-nama_penerima" required>
            <label for="add-nama_penerima">Nama Penerima</label>
        </div>

        <div class="input-field col s12">
            <textarea name="alamat_penerima" id="add-alamat_penerima" class="materialize-textarea" required></textarea>
            <label for="add-alamat_penerima">Alamat Penerima</label>
        </div>

        <div class="input-field col s12 m6">
            <input type="text" name="no_telp_penerima" id="add-no_telp_penerima" required>
            <label for="add-no_telp_penerima">No. Telepon Penerima</label>
        </div>

        <div class="input-field col s12 m6">
            <input type="number" name="biaya" id="add-biaya" min="0" step="0.01" required>
            <label for="add-biaya">Biaya (Rp)</label>
        </div>

        <div class="input-field col s12 m6">
            <input type="number" name="bobot" id="add-bobot" min="0" step="0.01" required>
            <label for="add-bobot">Bobot (kg)</label>
        </div>

        <div class="input-field col s12">
            <textarea name="keterangan" id="add-keterangan" class="materialize-textarea" required></textarea>
            <label for="add-keterangan">Keterangan Umum</label>
        </div>

        <div class="row">
            <div class="input-field col s12 center">
                <button class="btn waves-effect waves-light green" type="submit"><i class="material-icons left">save</i>Simpan</button>
            </div>
        </div>
    </form>
</div>

<!-- Form Edit Jastip -->
<div class="popup side" data-page="edit">
    <h1>Edit Jasa Titip</h1>
    <br>
    <form id="form-edit" class="row" enctype="multipart/form-data">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <input type="hidden" name="id" id="edit-id">

        <!-- Informasi Pengiriman -->
        <div class="col s12">
            <h5>Informasi Pengiriman</h5>
        </div>

        <div class="input-field col s12 m6">
            <input type="text" name="nomor_resi" id="edit-nomor_resi" required>
            <label for="edit-nomor_resi">Nomor Resi</label>
        </div>

        <div class="input-field col s12 m6">
            <input type="text" name="nama_penerima" id="edit-nama_penerima" required>
            <label for="edit-nama_penerima">Nama Penerima</label>
        </div>

        <div class="input-field col s12">
            <textarea name="alamat_penerima" id="edit-alamat_penerima" class="materialize-textarea" required></textarea>
            <label for="edit-alamat_penerima">Alamat Penerima</label>
        </div>

        <div class="input-field col s12 m6">
            <input type="text" name="no_telp_penerima" id="edit-no_telp_penerima" required>
            <label for="edit-no_telp_penerima">No. Telepon Penerima</label>
        </div>

        <div class="input-field col s12 m6">
            <input type="number" name="biaya" id="edit-biaya" min="0" step="0.01" required>
            <label for="edit-biaya">Biaya (Rp)</label>
        </div>

        <div class="input-field col s12 m6">
            <input type="number" name="bobot" id="edit-bobot" min="0" step="0.01" required>
            <label for="edit-bobot">Bobot (kg)</label>
        </div>

        <div class="input-field col s12">
            <textarea name="keterangan" id="edit-keterangan" class="materialize-textarea" required></textarea>
            <label for="edit-keterangan">Keterangan Umum</label>
        </div>

        <!-- status -->
        <div class="input-field col s12">
            <select name="status" id="edit-status" required>
                <option value="" disabled selected>Pilih Status</option>
                <option value="Pending">Pending</option>
                <option value="Proses Pengiriman">Proses Pengiriman</option>
                <option value="Sampai di Tujuan">Sampai di Tujuan</option>
                <option value="Selesai">Selesai</option>
                <option value="Batal">Batal</option>
            </select>
            <label>Status Jastip</label>
        </div>

        <div class="input-field col s12">
            <textarea name="catatan" id="edit-catatan" class="materialize-textarea"></textarea>
            <label for="edit-catatan">Catatan Admin</label>
        </div>

        <div class="row">
            <div class="input-field col s12 center">
                <button class="btn waves-effect waves-light green" type="submit"><i class="material-icons left">save</i>Simpan</button>
            </div>
        </div>
    </form>
</div>

<!-- Popup Detail Jastip -->
<div class="popup side" data-page="detail">
    <h1>Detail Jasa Titip</h1>
    <br>
    <div class="row">
        <div class="col s12">
            <p><strong>Nomor Resi:</strong> <span id="detail-nomor_resi"></span></p>
            <p><strong>Nama Penerima:</strong> <span id="detail-nama_penerima"></span></p>
            <p><strong>Alamat Penerima:</strong> <span id="detail-alamat_penerima"></span></p>
            <p><strong>No. Telepon Penerima:</strong> <span id="detail-no_telp_penerima"></span></p>
            <p><strong>Biaya:</strong> <span id="detail-biaya"></span></p>
            <p><strong>Bobot:</strong> <span id="detail-bobot"></span></p>
            <p><strong>Keterangan:</strong> <span id="detail-keterangan"></span></p>
            <p><strong>Status:</strong> <span id="detail-status"></span></p>
            <p><strong>Catatan Admin:</strong> <span id="detail-catatan"></span></p>
            <p><strong>Tanggal:</strong> <span id="detail-tanggal"></span></p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const table = {
        jastip: $("#table-jastip").DataTable({
            responsive: true,
            ajax: {
                url: baseUrl + "/api/jastip",
                dataSrc: "",
            },
            order: [
                [7, "desc"]
            ],
            columns: [{
                    title: "#",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },

                {
                    title: "Nomor Resi",
                    data: "nomor_resi"
                },
                {
                    title: "Penerima",
                    data: "nama_penerima"
                },
                {
                    title: "Biaya",
                    data: "biaya",
                    render: function(data) {
                        return data ? 'Rp ' + parseInt(data).toLocaleString('id-ID') : '-';
                    }
                },
                {
                    title: "Bobot",
                    data: "bobot",
                    render: function(data) {
                        return data ? data + ' kg' : '-';
                    }
                },
                {
                    title: "Status",
                    data: "status",
                    render: function(data) {
                        // Mapping status ke badge warna
                        const statusMap = {
                            'Pending': '<span class="badge orange">Pending</span>',
                            'Proses Pengiriman': '<span class="badge blue">Proses Pengiriman</span>',
                            'Sampai di Tujuan': '<span class="badge teal">Sampai di Tujuan</span>',
                            'Selesai': '<span class="badge green">Selesai</span>',
                            'Batal': '<span class="badge red">Batal</span>'
                        };
                        return statusMap[data] || `<span class="badge grey">${data}</span>`;
                    }
                },
                {
                    title: "Tanggal",
                    data: "created_at",
                    render: function(data) {
                        return new Date(data).toLocaleDateString('id-ID');
                    },
                },
                {
                    title: "Aksi",
                    data: "id",
                    render: (data, type, row) => {
                        return `<div class="table-control">
            <a role="button" class="btn waves-effect waves-light btn-action btn-popup blue" data-target="detail" data-action="detail" data-id="${data}"><i class="material-icons">info</i></a>
            <a role="button" class="btn waves-effect waves-light btn-action btn-popup orange darken-2" data-target="edit" data-action="edit" data-id="${data}"><i class="material-icons">edit</i></a>
            <a role="button" class="btn waves-effect waves-light btn-action red" data-action="delete" data-id="${data}"><i class="material-icons">delete</i></a>
          </div>`;
                    },
                },
            ],
        }),
    };

    // Submit form tambah jastip
    $("form#form-add").on("submit", function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        const elements = form.elements;
        for (let i = 0, len = elements.length; i < len; ++i) {
            elements[i].readOnly = true;
        }

        $.ajax({
            type: "POST",
            url: baseUrl + "/api/jastip",
            data: formData,
            contentType: false,
            processData: false,
            success: (data) => {
                form.reset();
                table.jastip.ajax.reload();
                if (data.messages) {
                    $.each(data.messages, function(icon, text) {
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
    $("form#form-edit").on("submit", function(e) {
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
            url: baseUrl + "/api/jastip/" + id,
            data: formData,
            contentType: false,
            processData: false,
            success: (data) => {
                form.reset();
                table.jastip.ajax.reload();
                if (data.messages) {
                    $.each(data.messages, function(icon, text) {
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
    $("body").on("click", ".btn-action", function(e) {
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
                            url: baseUrl + "/api/jastip/" + id,
                            cache: false,
                            success: (data) => {
                                table.jastip.ajax.reload();
                                if (data.messages) {
                                    $.each(data.messages, function(icon, text) {
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
                    url: baseUrl + "/api/jastip/" + id,
                    success: (data) => {
                        $("form#form-edit")[0].reset();

                        // Isi data ke form edit
                        $("#edit-id").val(data.id);
                        $("#edit-nomor_resi").val(data.nomor_resi);
                        $("#edit-nama_penerima").val(data.nama_penerima);
                        $("#edit-alamat_penerima").val(data.alamat_penerima);
                        $("#edit-no_telp_penerima").val(data.no_telp_penerima);
                        $("#edit-biaya").val(data.biaya);
                        $("#edit-bobot").val(data.bobot);
                        $("#edit-keterangan").val(data.keterangan);
                        $("#edit-status").val(data.status);
                        $("#edit-catatan").val(data.catatan);

                        // Inisialisasi ulang komponen Materialize
                        M.updateTextFields();
                        M.textareaAutoResize($("#edit-alamat_penerima"));
                        M.textareaAutoResize($("#edit-keterangan"));
                        M.textareaAutoResize($("#edit-catatan"));
                        M.FormSelect.init($("#edit-status"));
                    },
                });
                break;

            case "detail":
                $.ajax({
                    url: baseUrl + "/api/jastip/" + id,
                    success: (data) => {
                        // Isi data ke popup detail
                        $("#detail-nomor_resi").text(data.nomor_resi || '-');
                        $("#detail-nama_penerima").text(data.nama_penerima || '-');
                        $("#detail-alamat_penerima").text(data.alamat_penerima || '-');
                        $("#detail-no_telp_penerima").text(data.no_telp_penerima || '-');
                        $("#detail-biaya").text(data.biaya ? 'Rp ' + parseInt(data.biaya).toLocaleString('id-ID') : '-');
                        $("#detail-bobot").text(data.bobot ? data.bobot + ' kg' : '-');
                        $("#detail-keterangan").text(data.keterangan || '-');
                        $("#detail-catatan").text(data.catatan || '-');
                        $("#detail-tanggal").text(new Date(data.created_at).toLocaleDateString('id-ID'));

                        // Format status
                        const statusMap = {
                            'pending': 'Pending',
                            'proses': 'Proses Pengiriman',
                            'selesai': 'Selesai',
                            'batal': 'Batal'
                        };
                        $("#detail-status").text(statusMap[data.status] || data.status);
                    },
                });
                break;
        }
    });

    $(document).ready(function() {
        // Inisialisasi select
        M.FormSelect.init(document.querySelectorAll("select"));

        // Inisialisasi textarea
        M.textareaAutoResize($("textarea"));

        // Load data awal
        table.jastip.ajax.reload();
        $(".preloader").slideUp();
    });
</script>
<?= $this->endSection() ?>