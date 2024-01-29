@extends('backend.layout')



@section('content')

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-2">
                    <h5 class="mt-2 mb-3" style="margin-left:15px;">Form Fields</h5>
                    <form>
                        <div class="row">
                            <div class="col-md-12">
                                <select name="category" class="form-control catData">
                                    <option>Select</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <h3 class="text-light" style="font-size:15px;padding:10px 10px;border:1px solid #e8ebf0;border-radius:4px;margin-bottom:6px;background:#08c;border-color:#08c;">
                                    All Categories</h3>
                                <div class="card p-2 mt-2" id="dataRowContainer" style="border:1px solid #e8ebf0; border-radius:4px;">
                                    <div class=" ml-2" id="dataRow"> </div>
                                </div>
                            </div>
                        </div>
                        <div class="parentdiv" id="form_div" style="margin-left:-2px; margin-top: 20px;"> </div>
                        <div class="row" style="margin-left:-2px;" id="form_btn_submit">
                            <div class="col-md-12 mt-3"> <a id='submit-form' class="btn btn-outline-primary btn-sm mb-2">Submit</a> </div>
                        </div>
                    </form>
                    <div class="row" style="margin-left:-2px;" id="form_btn">
                        <div class="col-md-12 mt-3">
                            <input type="hidden" value="0" id="fval">
                            <input type="hidden" value="0" id="chval">
                            <button class="btn btn-outline-secondary btn-sm textfield mb-2"> <i class="fa fa-plus"></i> Text Field</button>
                            <button class="btn btn-outline-secondary btn-sm textarea mb-2"> <i class="fa fa-plus"></i> Text Area</button>
                            <button class="btn btn-outline-secondary btn-sm checkbox mb-2"> <i class="fa fa-plus"></i> Checkbox</button>
                            <button class="btn btn-outline-secondary btn-sm radio mb-2"> <i class="fa fa-plus"></i> Radio Button</button>
                            <button class="btn btn-outline-secondary btn-sm dropdown mb-2"> <i class="fa fa-plus"></i> Drop Down</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>


<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function() {
    let lastcid = 1;
    $('#form_btn').hide();
    $('#submit-form').hide();
    $('#dataRowContainer').hide();
    $.ajax({
        'type': "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        'url': "{{ url('/admin/car-specification/categoryData') }}",
        success: function(data) {
           // alert(data);
            $.each(data, function(key, value) {
                $('.catData').append(`<option value="${value.id}">${value.name}</option>`);
            });
        }
    });
    $(document).on('click', '.textfield', function() {
        var fval = $('#fval').val();
        fval++;
        $('#form_div').append('<div class="container-fluid">            <div class="row   mb-2" id="div' + fval + '" name="input"><div class="col-sm-4"><h6 class="mt-2">Text Field <i class="ml-4 fa text-danger fa-trash delete" id="' + fval + '"></i>  </h6></div>  <div class="col-sm-8">    <div class="input-group mb-3">      <input type="text" name="textfield-label[]" class="form-control" placeholder="Enter a Label">      <div class="input-group-append">        <span class="input-group-text">          <input name="textfield-chk[]" type="checkbox" class="" style="margin-right:5px;"> Required field </span>      </div>    </div>  </div> <hr  id="hr' + fval + '" style="margin:10px; flex: 100%; "></div></div>');
        $('#fval').val(fval);
    });
    $(document).on('click', '.textarea', function() {
        var fval = $('#fval').val();
        fval++;
        $('#form_div').append('<div class="container-fluid"><div class="row mb-2" id="div' + fval + '" name="textarea"><div class="col-sm-4"><h6 class="mt-2">Text Area <i class="ml-4 fa text-danger fa-trash delete" id="' + fval + '"></i> </h6></div><div class="col-sm-8"><div class="input-group mb-3"><input type="text" name="textarea-label[]" class="form-control" placeholder="Enter a Label"><div class="input-group-append"><span class="input-group-text"><input name="textarea-chk[]" type="checkbox" class="" style="margin-right:5px;"> Required field</span></div></div></div><hr id="hr' + fval + '" style="margin:10px; flex: 100%;"></div></div>');
        $('#fval').val(fval);
    });
    $(document).on('click', '.checkbox', function() {
        var fval = $('#fval').val();
        fval++;
        $('#form_div').append('<div class="container-fluid"><div class="row mb-2" id="div' + fval + '" name="checkbox"><input type="hidden" value="' + fval + '" id="fchkvalue' + fval + '"><div class="col-sm-4"><h6 class="mt-2">Checkbox <i class="ml-4 fa text-danger fa-trash delete" id="' + fval + '"></i> </h6></div><div class="col-sm-8"><div class="input-group mb-3"><input type="text" name="checkbox-label[]" class="form-control" placeholder="Enter a Label"><div class="input-group-append"><span class="input-group-text"><input name="textfield-chk[]" type="checkbox" class="" style="margin-right:5px;"> Required field</span></div></div></div></div><div class=" div' + fval + '" id="divchk' + fval + '"><div class="row div' + fval + '" id="div' + fval + 'chkrow1" style="margin-left: -5px;"><div class="col-sm-4"></div><div class="col-sm-6 mb-3"><input type="text" name="checkbox-child-label[]" class="form-control" placeholder="Enter a Label"></div><div class="col-sm-2"><i class="ml-4 fa text-danger fa-trash childdelete" id="' + fval + 'chkrow1"" style="margin-top:10px;"></i></div></div></div><div class="row div' + fval + '"><div class="col-sm-4"></div>      <div class="col-4"><button type="button" data="' + fval + '" class="btn btn-outline-secondary btn-sm morechk mb-2"> <i class="fa fa-plus"></i> Checkbox</button></div></div><hr id="hr' + fval + '" style="margin:10px;"></div>');
        $('#fval').val(fval);
    });
    $(document).on('click', '.radio', function() {
        var fval = $('#fval').val();
        fval++;
        $('#form_div').append('<div class="container-fluid">  <div class="row mb-2" id="div' + fval + '" name="radio">    <input type="hidden" value="' + fval + '" id="fradiovalue' + fval + '">    <div class="col-sm-4">      <h6 class="mt-2">Radio Button <i class="ml-4 fa text-danger fa-trash delete" id="' + fval + '"></i>      </h6>    </div>    <div class="col-sm-8">      <div class="input-group mb-3">        <input type="text" name="radio-label[]" class="form-control" placeholder="Enter a Label">        <div class="input-group-append">          <span class="input-group-text">            <input name="textfield-chk[]" type="checkbox" class="" style="margin-right:5px;"> Required field </span>        </div>      </div>    </div>    </div>    <div class=" div' + fval + '" id="divradio' + fval + '">      <div class="row div' + fval + '" id="div' + fval + 'radiorow1" style="margin-left: -5px;">         <div class="col-sm-4"></div>        <div class="col-sm-6 mb-3">          <input type="text" name="radio-child-label[]" class="form-control" placeholder="Enter a Label">        </div>        <div class="col-sm-2 ">          <i class="ml-4 fa text-danger fa-trash childdelete" id="' + fval + 'radiorow1"" style=" margin-top:10px;"></i>        </div>      </div>    </div>    <div class="row div' + fval + '">      <div class="col-sm-4"></div>      <div class="col-4">        <button type="button" data="' + fval + '" class="btn btn-outline-secondary btn-sm moreradio mb-2">          <i class="fa fa-plus"></i> Radio Button </button>      </div>    </div></div>');
        $('#fval').val(fval);
    });
    $(document).on('click', '.dropdown', function() {
        var fval = $('#fval').val();
        var chval = $('#chval').val();
        fval++;
        chval++;
        $('#form_div').append('<div class="container-fluid">  <div class="row mb-2" id="div' + fval + '" name="select">    <input type="hidden" value="' + fval + '" id="fdropdownvalue' + fval + '">    <div class=" col-sm-4">      <h6 class="mt-2">Drop Down <i class="ml-4 fa text-danger fa-trash delete" id="' + fval + '"></i>      </h6>    </div>    <div class="col-sm-8">      <div class="input-group mb-3">        <input type="text" name="dropdown-label[]" class="form-control" placeholder="Enter a Label">        <div class="input-group-append">          <span class="input-group-text">            <input name="textfield-chk[]" type="checkbox" class="" style="margin-right:5px;"> Required field </span>        </div>      </div>    </div>    </div>    <div class=" div' + fval + '" id="divdropdown' + fval + '">      <div class="row div' + fval + '" id="div' + fval + 'dropdownrow1" style="margin-left: -5px;">        <div class="col-sm-4" ></div>        <div class="col-sm-6 mb-3" >          <input type="text" name="dropdown-child-label[]" class="form-control" placeholder="Enter a Label">        </div>        <div class="col-sm-2 ">          <i class="ml-4 fa text-danger fa-trash childdelete" id="' + fval + 'dropdownrow1"" style=" margin-top:10px;"></i>        </div>      </div>    </div>    <div class="row div' + fval + '">      <div class="col-sm-4"></div>      <div class="col-4">        <button type="button" data="' + fval + '" class="btn btn-outline-secondary btn-sm moredropdown mb-2">          <i class="fa fa-plus"></i> Option </button>      </div>    </div></div>');
        $('#fval').val(fval);
    });
    $(document).on('click', '.delete', function() {
        var id = $(this).attr('id');
        $("#div" + id).remove();
        $(".div" + id).remove();
        $("#hr" + id).remove();
    });
    $(document).on('click', '.childdelete', function() {
        var id = $(this).attr('id');
        console.log(id);
        $("#div" + id).remove();
    });
    $(document).on('click', '.morechk', function() {
        var id = $(this).attr('data');
        var fval = $('#fchkvalue' + id).val();
        fval++;
        $('#divchk' + id).append('<div class="row div' + id + '" id="div' + id + 'chkrow' + fval + '" style="margin-left: -5px;"><div class="col-sm-4"></div><div class="col-sm-6 mb-3"><input type="text" name="checkbox-label[]" class="form-control" placeholder="Enter a Label"></div><div class="col-sm-2 col-1"><i class="ml-4 fa text-danger fa-trash childdelete" id="' + id + 'chkrow' + fval + '" style="margin-top:10px;"></i></div></div></div>');
        $('#fchkvalue' + id).val(fval);
    });
    $(document).on('click', '.moreradio', function() {
        var id = $(this).attr('data');
        var fval = $('#fradiovalue' + id).val();
        fval++;
        $('#divradio' + id).append('<div class="row div' + id + '" id="div' + id + 'radiorow' + fval + '" style="margin-left: -5px;"><div class="col-sm-4"></div><div class="col-sm-6 mb-3"><input type="text" name="radio-label[]" class="form-control" placeholder="Enter a Label"></div><div class="col-sm-2 col-1"><i class="ml-4 fa text-danger fa-trash childdelete" id="' + id + 'radiorow' + fval + '" style="margin-top:10px;"></i></div></div></div>');
        $('#fradiovalue' + id).val(fval);
    });
    $(document).on('click', '.moredropdown', function() {
        var id = $(this).attr('data');
        var fval = $('#fdropdownvalue' + id).val();
        fval++;
        $('#divdropdown' + id).append('<div class="row div' + id + '" id="div' + id + 'dropdownrow' + fval + '" style="margin-left: -5px;"><div class="col-sm-4"></div><div class="col-sm-6 mb-3"><input type="text" name="dropdown-label[]" class="form-control" placeholder="Enter a Label"></div><div class="col-sm-2 col-1"><i class="ml-4 fa text-danger fa-trash childdelete" id="' + id + 'dropdownrow' + fval + '" style="margin-top:10px;"></i></div></div></div>');
        $('#fdropdownvalue' + id).val(fval);
        console.log(fval);
    });
    $(".btn-light:eq(1)").click(function() {
        $("[data-toggle='popover']").popover('hide');
    });
    $(document).on('click', '.full', function() {
        let cid = $(this).attr('data');
        fetchCategoryData2(cid);
    });
    $(document).on('change', '.catData', function() {
        let cid = $(this).val();
        fetchCategoryData2(cid);
    });
    let dropdown = '';
    async function optionData(cid) {
        $.ajax({
            url: "{{ url('admin/fetchOptions') }}",
            type: "POST",
            data: {
                id: cid
            },
            success: function(res) {
                console.log(res);
            },
        });
    }

    function fetchCategoryData2(parent) {
        var cid = lastcid = parent;
        $.ajax({
            'type': "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
    'url': "{{ url('/admin/car-specification/fetch-subcategory-data') }}",
            data: {
                cid: cid
            },
            success: function(data) {
                if(data.length > 0) {
                    $('.nosub').hide();
                    $('.addsub').show();
                    $('#dataRowContainer').show();
                    $('#form_btn').hide();
                    $('#submit-form').hide();
                } else {
                    $('.nosub').show();
                    $('.addsub').show();
                    $('#form_btn').show();
                    $('#dataRowContainer').hide();
                    $('#submit-form').show();
                   // alert(cid);
                    $.ajax({
                        url: "{{ url('/admin/car-specification/fetchForm') }}",
                        type: "POST",
                        data: {
                            id: cid
                        },
                        success: function(res) {
                            $('#form_div').html(res.html);
                            $('#fval').val(res.divlen);
                        },
                    });
                }
                $('#dataRow').html("");
                $.each(data, function(key, value) {
                    $('#dataRow').append(`
                                <div class=" mb-2 p-2 border border-secondary"><div class="col-md-6 col-6 full" data="${value.id}">
                                    <i class="fal fa-bars" value="${value.id}" id="info"></i>&nbsp;&nbsp;&nbsp;${value.name}
                                </div>
                                
                                    
                                </div>
                            </div>
                        `);
                });
            }
        });
    }
    $(document).on('click', '#submit-form', function() {
        let selectValue = '';
        let textareaValue = '';
        let inputValue = '';
        let radioValue = '';
        let checkBoxValue = '';
        let optionValue = [];
        let radioOptionValue = [];
        let checkBoxOptionValue = [];
        let data = [];
        let formDivChildCount = $('#form_div').children().length;
        $('#form_div').children().each(function() {
            let childID = $(this).attr('id');
            let divName = $('#' + childID).attr('name');
            if(divName == 'select') {
                let div = $('#' + childID).children();
                selectValue = $(div[2]).find('input').val();
                let i = 0;
                $(div[4]).children().each(function() {
                    let optDiv = $(this).attr('id');
                    optionValue[i] = $('#' + optDiv).find('input').val();
                    i++;
                });
                data.push({
                    'label': selectValue,
                    'option': optionValue,
                    'type': 'select'
                });
                optionValue = [];
            } else if(divName == 'input') {
                let div = $('#' + childID).children();
                inputValue = $(div).find('input').val();
                data.push({
                    'label': inputValue,
                    'type': 'input',
                });
            } else if(divName == 'checkbox') {
                let i = 0;
                let div = $('#' + childID).children();
                checkBoxValue = $(div[2]).find('input').val();
                $(div[4]).children().each(function() {
                    let optDiv = $(this).attr('id');
                    checkBoxOptionValue[i] = $('#' + optDiv).find('input').val();
                    i++;
                });
                data.push({
                    'label': checkBoxValue,
                    'option': checkBoxOptionValue,
                    'type': 'checkbox',
                });
                checkBoxOptionValue = [];
            } else if(divName == 'textarea') {
                let div = $('#' + childID).children();
                textareaValue = $(div).find('input').val();
                data.push({
                    'label': textareaValue,
                    'type': 'textarea',
                });
            } else if(divName == 'radio') {
                let i = 0;
                let div = $('#' + childID).children();
                radioValue = $(div[2]).find('input').val();
                $(div[4]).children().each(function() {
                    let optDiv = $(this).attr('id');
                    radioOptionValue[i] = $('#' + optDiv).find('input').val();
                    i++;
                });
                data.push({
                    'label': radioValue,
                    'option': radioOptionValue,
                    'type': 'radio',
                });
                radioOptionValue = [];
            }
        });
        $.ajax({
            url: "{{ url('/admin/car-specification/save-form-structure') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                form: data,
                cid: lastcid,
            },
            success: function(response) {
                if(response == 'success') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        text: 'Structure Saved',
                        showConfirmButton: false,
                        timer: 1000
                    })
                } else if(response == 'fail') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: "Something went wrong!",
                        text: 'Something went wrong!'
                    })
                }
            }
        });
    });
});
</script>
<style>
   .parentdiv .row {
        display: blocnk !important;
    }
    #form_div {
        margin-left: 20px !important;
        margin-right: 20px !important;
        font-size: 14px !important;
    }
</style>
