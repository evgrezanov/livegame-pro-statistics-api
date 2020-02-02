$('#marketType').change =(function() {
  if ($(this).val() == "handicap") {
    $('#handicapDiv').show();
    $('#totalDiv').hide();
  } else {
    $('#totalDiv').show();
    $('#handicapDiv').hide();
  }
});
$("#marketType").trigger("change");
