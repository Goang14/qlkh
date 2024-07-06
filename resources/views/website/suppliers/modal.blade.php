<!-- Modal create employee -->
<div class="modal fade" id="createSupplier" tabindex="-1" aria-labelledby="createSupplier" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createUserModalLabel">Tạo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="createSuppliersNew" class="needs-validation">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-8 mb-3">
                            <label for="name" class="form-label">Tên nhà cung cấp
                                <span class="badge bg-danger"></span>
                            </label>
                            <input name="name" type="text" class="form-control" id="name">
                            <span id="error_name" class="invalid-feedback"></span>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="contact_name" class="form-label">Tên liên lạc
                                <span class="badge bg-danger"></span>
                            </label>
                            <input name="contact_name" type="text" class="form-control" id="contact_name">
                            <span id="error_contact_name" class="invalid-feedback"></span>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="phone" class="form-label">Số điện thoại
                                <span class="badge bg-danger"></span>
                            </label>
                            <input name="phone" type="text" class="form-control" id="phone">
                            <span id="error_phone" class="invalid-feedback"></span>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="email" class="form-label">Email
                                <span class="badge bg-danger"></span>
                            </label>
                            <input name="email" type="text" class="form-control" id="email">
                            <span id="error_email" class="invalid-feedback"></span>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="address" class="form-label">Địa chỉ
                                <span class="badge bg-danger"></span>
                            </label>
                            <input name="address" type="text" class="form-control" id="address">
                            <span id="error_address" class="invalid-feedback"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary"
                    onclick="createSuppliers()">Lưu</button>
            </div>
        </div>
    </div>
</div>


<script>
    function createSuppliers(){
        let formData = new FormData($('form#createSuppliersNew')[0]);
        let url = "{{ route('suppliers.create') }}"
        $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            }).done(function() {
                $('#createSupplier').modal('hide');
                loadData();
            }).fail(function(err) {
                console.error(err);
            }).always(function(always) {
                alwaysAjax('createSuppliersNew', always);
        })
    }
</script>
