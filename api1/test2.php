<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>nth-child demo</title>
  <style>
  button {
    display: block;
    font-size: 12px;
    width: 100px;
  }
  div {
    float: left;
    margin: 10px;
    font-size: 10px;
    border: 1px solid black;
  }
  span {
    color: blue;
    font-size: 18px;
  }
  #inner {
    color: red;
  }
  td {
    width: 50px;
    text-align: center;
  }
  </style>
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>
 
<div>
  <button>:nth-child(even)</button>
  <button>:nth-child(odd)</button>
  <button>:nth-child(2n)</button>
  <button>:nth-child(0)</button>
</div>
<div class="test">
  <input type="text" class="name">
  <input type="text" class="pass">
  <input type="text" class="mail">


</div>
<div class="test">
  <input type="text" class="name">
  <input type="text" class="pass">
  <input type="text" class="mail">
</div>
<div id="ok">
  <input type="text" value="1">
  <input type="text" value="2">
  <input type="text" value="3">
<ul>
  <li>ok1</li>
  <li>ok2</li>
  <li>ok3</li>
  <li>ok4</li>
  <li>ok5</li>
</ul>
</div>
 
<span>tr<span id="inner"></span></span>
 
<script>


$( "button" ).click(function() {
var data=[];
  $.each($('.test'), function(index, val) {
var name = $(this).find('input.name').val();
var pass = $(this).find('input.pass').val();
var mail = $(this).find('input.mail').val();
var cur_data = {name:name,pass:pass,mail:mail};

data.push(cur_data);
     /* iterate through array or object */
  });
$.ajax({
   url: 'test3.php',
   type: 'POST',
   dataType: 'html',
   data: {data:data},
   success:function(data) {
   alert(data);
   }
 });;
  /* alert($("#ok ul li:nth-child(2)").html());
  
  alert($("#ok input:eq(0)").val());*/
 
});
</script>
 
</body>
</html>