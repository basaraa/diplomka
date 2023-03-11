//add
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

//delete
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

//edit subject
$(function () {
    $('.editForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'postHandlers/edit.php',
            data: $('.editForm').serialize(),
            success: function (data) {
                let result = JSON.parse(data)
                if(result.scs===false){
                    let FOSConstraint = result.FOSerr ? ("Zoznam kolízii pri štúdijnom odbore v ročníku v semestri pri predmetoch: "+result.FOSerr+"<br>") : '';
                    let RoomConstraint = result.RoomErr ? ("Zoznam kolízii v miestnostiach pri predmetoch: "+result.RoomErr+"<br>") : '';
                    let TeacherConstraint = result.TeacherErr ? ("Zoznam kolízii pri učiteľoch pri predmetoch (učiteľ:predmet): "+result.TeacherErr+"<br>") : '';
                    document.getElementById("modal_background3").style.display="block";
                    document.getElementsByClassName("modal_div3")[0].style.display="flex";
                    document.getElementById("modal_text3").innerHTML=FOSConstraint + RoomConstraint + TeacherConstraint;
                }
                else{
                    document.getElementById("modal_background").style.display="none";
                    document.getElementsByClassName("modal_div")[0].style.display="none";
                    document.getElementById("modal_background2").style.display="block";
                    document.getElementsByClassName("modal_div2")[0].style.display="flex";
                    document.getElementById("result_edit").innerHTML=result.msg;
                }
            },
            error: function (){
                alert ("Nastala chyba skúste to znova")
            }
        });
    })
});

//generate edit subject form
function generateEditForm(id,grade,year,semestre){
        $.ajax({
            type: 'post',
            url: 'postHandlers/editForm.php',
            data: {subjectId : id,grade:grade,year:year, semestre : semestre},
            success: function (data) {
                document.getElementById("modal_background").style.display="block";
                document.getElementsByClassName("modal_div")[0].style.display="flex";
                document.getElementById("modal_text").innerHTML=data
            },
            error: function (){
                alert ("Nastala chyba skúste to znova")
            }
        });
    }

//generate options by grade
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
function go_back2(){
    document.getElementById("modal_background3").style.display="none";
    document.getElementsByClassName("modal_div3")[0].style.display="none";
}

//reset single subject
function resetSingleSubject(id){
    $.ajax({
        type: 'post',
        url: 'postHandlers/reset.php',
        data: {id : id,type:0},
        success: function () {
            location.reload()
        },
        error: function (){
            alert ("Nastala chyba skúste to znova")
        }
    });
}
//reset all subjects in fieldOfStudy
function resetFieldOfStudySubjects(id){
    $.ajax({
        type: 'post',
        url: 'postHandlers/reset.php',
        data: {id : id,type:1},
        success: function () {
            location.reload()
        },
        error: function (){
            alert ("Nastala chyba skúste to znova")
        }
    });
}
