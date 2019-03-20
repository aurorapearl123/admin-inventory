"use strict";

$('.datepicker').datetimepicker({
  defaultDate: false,
  format: 'MMMM DD, YYYY',
  buttons : {
    showClear: true
  }
});
$('.timepicker').datetimepicker({
  defaultDate: false,
  format: 'LT',
  stepping: 10
});
autosize(document.querySelectorAll('textarea'));
$('.scrollable-wrap').scrollbar();

// Collapse Filter
$('#btn-filter').click( function(){
  $('#btn-filter').toggleClass('active');
  $('#filter-group,#btn-apply').collapse('toggle');
});

// Employee Profile - Family Members
$('#family-members tbody tr td, #profile-dutiestab tbody tr td').click(function(){
  $('#modal2').modal('show');
});
$('#family-members #edit, #profile-dutiestab #edit').click(function(){
  $('#modal4').modal('show');
});
$('#recordlog,#family-members #view, #profile-dutiestab #view').click(function(){
  $('#modal5').modal('show');
});
$('#family-members tbody tr td:last-child, #profile-dutiestab tbody tr td:last-child').off('click');

// Alert Prompt
$('#sweet-basic').click(function(){
  swal('Any fool can use a computer')
});
$('#sweet-reverse,#delete').click(function(){
  swal({
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  type: 'warning',
  showCancelButton: true,
  confirmButtonClass: "btn btn-primary btn-raised pill",
  cancelButtonClass: 'btn btn-outline-danger btn-raised pill',
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.value) {
      swal(
        'Deleted!',
        'Your file has been deleted.',
        'success'
      )
    }
  })
});
$('#sweet-both,#save').click(function(){
  swal({
    title: 'Are you sure?',
    text: "You will change the new profile picture!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: 'No, cancel!',
    confirmButtonText: 'Yes',
    cancelButtonClass: 'btn btn-outline-danger btn-raised pill',
    confirmButtonClass: 'btn btn-primary btn-raised pill',
    buttonsStyling: false,
    reverseButtons: true
  }).then((result) => {
    if (result.value) {
      $('.modal').modal('hide');
      swal(
        'Successful!',
        'Your profile picture has been change',
        'success'
      )
    }
  })
});
$('#sweet-noti').click(function(){
  swal({
    position: 'top-end',
    type: 'success',
    title: 'Your work has been saved',
    showConfirmButton: false,
    timer: 10000
  })
});