$(function () {
    $('.addForm').submit( function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'postHandlers/add.php',
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
            url: 'postHandlers/delete.php',
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
    $('.editForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'postHandlers/edit.php',
            data: $('.editForm').serialize(),
            success: function (data) {
                document.getElementById("modal_background").style.display="none";
                document.getElementsByClassName("modal_div")[0].style.display="none";
                document.getElementById("modal_background2").style.display="block";
                document.getElementsByClassName("modal_div2")[0].style.display="flex";
                document.getElementById("result_edit").innerHTML=data;
            },
            error: function (){
                alert ("Nastala chyba skúste to znova")
            }
        });
    })
});

$(function () {
    $('.edit_subject').on('click', function (e) {
        let id=this.id;
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'postHandlers/editForm.php',
            data: {subjectId : id},
            success: function (data) {
                document.getElementById("modal_background").style.display="block";
                document.getElementsByClassName("modal_div")[0].style.display="flex";
                document.getElementById("modal_text").innerHTML=data
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
function go_back(){
    document.getElementById("modal_background").style.display="none";
    document.getElementsByClassName("modal_div")[0].style.display="none";
}

