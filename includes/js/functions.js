function sweetalert(string, state, url, text)  {
    if(url != ""){
      swal({
        title:string,
        icon: state,
        text:text,
          buttons: {
            ok: 'Okay',
          },
        })
        .then((value) => {
          switch (value) {
            default:
              document.location = url;
          }
        })
    }else{
      swal({
        title: string,
        text:text,
        icon: state
      });
    }
}

function w3_open() {
  document.getElementById("mySidebar").style.display = "block";
  document.getElementById("myOverlay").style.display = "block";
}

function w3_close() {
  document.getElementById("mySidebar").style.display = "none";
  document.getElementById("myOverlay").style.display = "none";
}

function accordion(id) {
  var x = document.getElementById(id);
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else {
    x.className = x.className.replace(" w3-show", "");
  }
}

function go_ahead(next_form,form_to_hide){
  $('#'+next_form).removeClass('w3-hide');
  $('#'+form_to_hide).addClass('w3-hide');
}

function go_back(previous_form, form_to_hide){
  $('#'+previous_form).removeClass('w3-hide');
  $('#'+form_to_hide).addClass('w3-hide');
}

function open_saving_btn(nb_id) {
  $('#nb_id_fk').val(nb_id);
}

function toggle_show(id) {
  $('#'+id).toggleClass('w3-show');
}

function toggle_hide(id) {
  $('#'+id).toggleClass('w3-hide');
}

function data_to_id(id, data){
  $('#'+id).val(data);
}

function append_to_id(id, data){
  $('#'+id).html(data);
}

function isNumber(evt){
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true; 
}

function operator_type(op_type){
    $.ajax({
        url : "parse/bbps_recharge/get_operators.php?service_type="+op_type,
        type:"POST",
        data:{"op_type":op_type},
        beforeSend : function(){
            $(".operator").html("<option disabled selected value=''>Fetching...</option>");
        },
        success:function(data){
            $(".operator").html(data);
        },
    })
   }