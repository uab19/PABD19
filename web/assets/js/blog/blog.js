$(document).ready(function() {

    $("#form_image").change(function(){
        
        var input = this;

        if (input.files && input.files[0]) {
            var reader = new FileReader();
        
            reader.onload = function(e) {
              $('#image-preview').attr('src', e.target.result);
            }
        
            reader.readAsDataURL(input.files[0]);
        }
    });

    $("#image-checkbox").change(function(){

        if(this.checked) {
            $("#image-container").css("display", "block");
            
        } else {
            $("#form_image").val(null);
            $('#image-preview').attr('src',null);
            $("#image-container").css("display", "none");
        }
        
    });
    
});