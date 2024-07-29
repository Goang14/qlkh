@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="w-100 title-screen">Quản lý cầm đồ</div>
                        <div class="flex-shrink-1">
                            <button type="button" class="btn btn-primary w-90px" data-bs-toggle="modal"
                                data-bs-target="#create" onclick="clearModal()">
                                Thêm
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="pawn-datatable">
                        <div class="row mb-3">
                            <div class="col-12 col-sm-6 col-xl-4 pe-0">
                                <label for="project_client" class="form-label m-0">Tìm kiếm</label>
                                <div class="row pt-3">
                                    {{-- <div class="col-5">
                                        <select name="service_search" id="service_search" class="form-select">
                                            <option value="">Vui lòng chọn</option>
                                            @foreach ($service as $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    <div class="col-7">
                                        <div class="input-group">
                                            <input id="search-pawn" type="text" class="form-control" placeholder="Tìm kiếm"
                                                value="">
                                            <span class="input-group-text" id="basic-addon2"><i class="bi bi-search"></i></span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="table-container">
                            {{-- table data --}}
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col">ID</th>
                                        <th scope="col">Tên khách hàng</th>
                                        <th scope="col">Số điện thoại</th>
                                        <th scope="col">Dịch vụ</th>
                                        <th scope="col">Số tiền</th>
                                        <th scope="col">Tiền lãi</th>
                                        <th scope="col">Nội dung</th>
                                        <th scope="col">Hạn</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Địa chỉ</th>
                                        <th scope="col">Chức năng</th>
                                      </tr>
                                </thead>
                                <tbody id="data-table-body">
                                </tbody>
                            </table>
                        </div>
                        <div id="pagination-links">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('website.modal.create-modal')
    @include('website.modal.update-modal')
@endsection

@section('script')

<script>

    $(document).ready(function() {
        loadData();
    });
    let keyword = '';
    let pawnsData = {};

    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    function addParameterToURL(page) {
        let url = `{{ route('pawns.search') }}?page=${page}`;
        if (keyword !== '') {
            url +=`&keyword=${keyword}`
        }
        return url;
    }

    function formatDate1(dateString) {
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day} 00:00:00`;
    }

    function dateDifference(date1, date2) {
        const d1 = new Date(date1);
        const d2 = new Date(date2);
        d1.setHours(0, 0, 0, 0);
        d2.setHours(0, 0, 0, 0);
        const diffTime = d1 - d2;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays === 0 ? 1 : diffDays;
    }

    function formatPrice(number) {
        return number.toLocaleString('vi-VN', {currency: 'VND' });
    }

    function loadData(page = 1) {
        let url = addParameterToURL(page);
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
        }).done(function(response) {
            console.log(response);
            let date = new Date();
            const formattedDate = formatDate1(date);
            let dataTable = $('#pawn-datatable #data-table-body').empty();
            // render table data
            $.each(response.data, function(index, item) {
                let day = dateDifference(formattedDate, item.start_guarantee )
                const calculatedValue = Math.floor(((item.money_pawn.replace(/\./g, '')) * 3000 * day )/ 1000000);
                let row = `<tr id="tr-${item.id}" style="vertical-align: middle">
                <td class="text-center">${++index}</td>
                <td class="text-center">${item.name}</td>
                <td class="text-center">${item.phone}</td>
                <td class="text-center">${"Cầm đồ"}</td>
                <td class="text-center">${item.money_pawn + " VND"}</td>
                <td class="text-center">${formatPrice(calculatedValue) + " VND"}</td>
                <td class="text-center">${item.content ?? ''}</td>
                <td class="text-center">
                    ${formatDate(item.start_guarantee)} - ${formatDate(item.end_guarantee)}
                </td>
                <td class="text-center">${(item.status == 0 ? '<span class="text-success">Đang còn hạn</span>' : '<span class="text-danger">Hết hạn</span>')}</td>
                <td class="text-center">${item.address}</td>
                <td class="text-center">
                    <button
                        type="button"
                        data-bs-toggle="modal" data-bs-target="#update"
                        class="btn btn-warning btn-sm"
                        onclick="fillModal('${item.id}')">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <form action="{{ route('pawns.delete', ['id' => ':id']) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>`;
                row = row.replace(':id', item.id);
                dataTable.append(row);
                pawnsData[`${item.id}`] = item;
            });
            $('#pawn-datatable #pagination-links').html(pagination(response.pagination, 'loadData'));
        }).fail(function(err) {
            const errors = err?.responseJSON?.errors;
            if (typeof errors === 'object' && errors !== null && !(errors instanceof Array)) {
                for (var key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        $(`#${key}`).addClass('is-invalid');
                        $(`#error_${key}`).text(errors[key]);
                        $(`#error_${key}`).addClass('d-block');
                    }
                }
            }
        });
    }

    $('#pawn-datatable #search-pawn').keyup(function(event) {
        keyword = event?.target?.value ?? '';
        loadData();
    });

    function clearModal(){
        $('#create #name_customer').val('');
        $('#create #phone').val('');
        $('#create #email').val('');
        $('#create #address').val('');
        $('#create #type').val('');
        $('#create #repair_content').val('');
        $('#create #start_guarantee').val('');
        $('#create #end_guarantee').val('');
    }

    function fillModal(id) {
        $('#update').modal('show');
        $('#id_customer').val(pawnsData[`${id}`]['customer_id']);
        $('#id_pawn').val(id);
        let start_guarantee = pawnsData[`${id}`]['start_guarantee'].replace(' 00:00:00', '');
        let end_guarantee = pawnsData[`${id}`]['end_guarantee'].replace(' 00:00:00', '');
        if (id) {
            $('#updateNew #name_customer').val(pawnsData[`${id}`]['name']);
            $('#updateNew #phone').val(pawnsData[`${id}`]['phone']);
            $('#updateNew #email').val(pawnsData[`${id}`]['email']);
            $('#updateNew #address').val(pawnsData[`${id}`]['address']);
            $('#updateNew #type').val(pawnsData[`${id}`]['type']);
            $('#updateNew #product_id').val(pawnsData[`${id}`]['product_id']);
            $('#updateNew #content').val(pawnsData[`${id}`]['content']);
            $('#updateNew #money_pawn').val(pawnsData[`${id}`]['money_pawn']);
            $('#updateNew #start_guarantee').val(start_guarantee);
            $('#updateNew #end_guarantee').val(end_guarantee);
        }
    }
</script>
@endsection
