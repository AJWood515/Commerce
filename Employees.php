<!Doctype html>
<html>
<head>

  <script
    src="https://code.jquery.com/jquery-3.3.1.js"
    integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
    crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<link rel="stylesheet" type="text/css" href="CommerceSass/Employee.css">

</head>
<?php
include "DatabaseConnection.php";
try {
    $stmt = $conn->prepare("SELECT EmployeeID, company, LastName, FirstName, Address, City, Title FROM Employees");
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $json =array();
    $count = 0;
    foreach($stmt->fetchAll() as $k=>$v) {
        $json[] = $v;
        $count++;
    }
    $count = $stmt->rowCount();
    $jsonData = json_encode($json);
    file_put_contents('employeesJSON.json', $jsonData);
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>

<body class = "container col-lg-12">
  <div class = "row col-12">
    <div id = "results"  class = "col-9">
    Number of results:
      <input type="button"  value="2" >
      <input type="button"  value="3" >
      <input type="button"  value="5" >
    </div>
    <div>
      <input id="search" class = " col-8" type="text" placeholder="Search..">
    </div>
  </div>

<div class =" ">
  <table id ="table2" class ="table col-lg-12 table-striped" style = "border: 1px solid black ">
    <th id="thead">ID</th>
    <th id="thead">First Name</th>
    <th id="thead">Last Name</th>
    <th id="thead">Address</th>
    <th id="thead">City</th>
    <th id="thead">Job Title</th>
    <th id="thead">Company</th>
  </table>
</div>
  <div id = "page"></div>
</body>

<script>
//when document is ready
$(document).ready(function(){
  createTable();

  $("#search").keyup(function() {
       var value = this.value.toLowerCase().trim();

       $("#table2").find("tr").each(function(index) {
         var id =
           $(this).find("td").text().toLowerCase().trim();
           $(this).toggle(id.indexOf(value) !== -1);
       });
   });
});
function createTable(){

    var jsonArray = [];
     count = 0;
      //get json file
        $.getJSON("employeesJSON.json", function ShowJson(result){
            //iterate over array and build table
            $.each(result, function(i, field){
                $('#table2').append( '<tr><td>' + field["id"].replace(/\"/g, "") +'</td>'+'<td>'+
                field["first_name"].replace(/\"/g, "") +'</td>'+'<td>'+
                field["last_name"].replace(/\"/g, "") +'</td>'+'<td>'+
                field["email_address"].replace(/\"/g, "") +'</td>'+'<td>'+
                field["job_title"].replace(/\"/g, "") +'</td>'+'<td>'+
                field["company"].replace(/\"/g, "")+'</td>'+'</tr>' );

              //count number of entries for number of pages
                count = i;
            });//End of iteration and table built
            count = count +1;
          }); //End of get json file
        };
        $("#results").children().click(function(){
          for (var b = 0; b < 3; b++){
            $("#results").children().removeClass("active");
          }
          $(this).addClass("active");
          resPerPage();

      });
        function resPerPage(){
        var num = parseInt($(".active").val(),10);
        var numOfPages = Math.ceil((count)/num);
        count = count;
        for (var r = 0; r <= count; r++){
          $('#table2').find('tr:eq('+ r+')').removeClass("show").show();
        }
        // for loop to put a class "show" on the rows to show.
        for (var x = 0; x <= num; x++){
          $('#table2').find('tbody tr:eq('+x+')').addClass("show");
        }
        //statment to hide all other rows with out the show class
        $("tr").not(".show").hide();

        $("#page").empty();
        //for loop to creat the number of pages button required.

        for (var p = 1; p <= numOfPages; p++){
          $("#page").append('<button value = '+p +'>'+(p)+'</button>');
        }
        // show and hide number of entries per page depending on button clicked
        $("#page").children().click(function(){
          var buttonValue = 0;
          buttonValue = parseInt($(this).val(),10);
          //for loop to remove class show and and show all. Also add show class to header row.
          for (var r = 0; r <= count; r++){
            $('#table2').find('tr:eq('+ r+')').removeClass("show").show();
            $('#table2').find(' tr:eq(0)').addClass("show");
            $("#page").children().removeClass("current");
          }

          $(this).addClass("current");
          // variable to start for loop for entries and switch case to adjust number to show proper results.
          var ResNum = 0;
          switch (buttonValue){
            case 1:
               ResNum = 1;
              break;
            case 2:
              ResNum = (num * 1)+1 ;
              break;
            case 3:
              ResNum = (num * 2) + 1;
              break;
            case 4:
                ResNum = (num * 3) + 1;
                break;
            case 5:
                ResNum = (num * 4) + 1;
                break;
            }
          //for loop to add show class to entries to be shown.
          for (var c = (0 + ResNum); c <= (num * buttonValue); c++){
            $('#table2').find(' tr:eq('+c+')').addClass("show");
          }
          //hide row that dont have the show class
          $("tr").not(".show").hide();

        });// End of Pageclick function
      };

</script>
</html>
