$(function () {
    $('.addForm').submit( function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'add.php',
            data: $('.addForm').serialize(),
            success: function () {
                document.getElementById("modal_background").style.display="block";
                document.getElementsByClassName("modal_div")[0].style.display="flex";
            },
            error: function (){
                alert ("Nastala chyba skúste to znova")
            }
        });
    })
});

$(function () {
    $('.deleteForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'delete.php',
            data: $('.deleteForm').serialize(),
            success: function () {
                document.getElementById("modal_background").style.display="block";
                document.getElementsByClassName("modal_div")[0].style.display="flex";
            },
            error: function (){
                alert ("Nastala chyba skúste to znova")
            }
        });
    })
});
$(function () {
  $(".subjectFieldOfStudy").change(function(){
      let index = $(".subjectFieldOfStudy option:selected").index();
      let year=$("#year");
      year.empty();
      year.append($("<option></option>").attr("value",1).text("1. ročník"));
      year.append($("<option></option>").attr("value",2).text("2. ročník"));
      if (index===0)
          year.append($("<option></option>").attr("value",3).text("3. ročník"));
  })
});
function add (type,name){
    $.ajax({
        type: "post",
        url: "addInsertion.php",
        data:{
            type:type,
            name:name
        },
        success:function (data) {
            document.getElementById("modal_background").style.display="block";
            document.getElementsByClassName("modal_div")[0].style.display="flex";
            $('#modal_vrstva').html(data);
        }

    });

}