function prioritize(id,page) {
            if(confirm("Bạn chắc chắn muốn ưu tiên thanh toán cho SDT này?")){
                $.ajax({
                    url : '/ajax/phone/prioritize.php',
                    type : 'post',
                    dataType : 'json',
                    data : {
                        id : id
                    },
                    success : function(data) {
                        alert(data.msg);
                        if(data.code === 0) {
                            load(page);
                        }
                    }
                });
            }
}

  function pausePhone(id,page) {
            if(confirm("Bạn chắc chắn muốn tạm dừng thanh toán cho SDT này?")){
                $.ajax({
                    url : '/ajax/pause.php',
                    type : 'post',
                    dataType : 'json',
                    data : {
                        id : id
                    },
                    success : function(data) {
                        alert(data.msg);
                        if(data.code === 0) {
                            load(page);
                        }
                    }
                });
            }
        }
                function startPhone(id,page) {
            if(confirm("Bạn chắc chắn muốn Bật thanh toán cho SDT này?")){
                $.ajax({
                    url : '/ajax/start.php',
                    type : 'post',
                    dataType : 'json',
                    data : {
                        id : id
                    },
                    success : function(data) {
                        alert(data.msg);
                        if(data.code === 0) {
                            load(page);
                        }
                    }
                });
            }
        }


                function refund(id,page) {
            if(confirm("Bạn chắc chắn muốn hoàn tiền cho SDT này?")){
                $.ajax({
                    url : '/ajax/refund.php',
                    type : 'post',
                    dataType : 'json',
                    data : {
                        id : id
                    },
                    success : function(data) {
                        alert(data.msg);
                        if(data.code === 0) {
                             load(page);
                        }
                    }
                });
            }
        }    
        $(function () {

  $("#username").autocomplete({
    source: function (request, response) {

      $.ajax({
        url: "/ajax/transfer/get.php",
        type: 'post',
        dataType: "json",
        data: {
          search: request.term
        },
        success: function (data) {
          response(data);
        }
      });
    },
    select: function (event, ui) {
      $('#username').val(ui.item.name); // display the selected text
      $('#userphone').val(ui.item.phone); // save selected id to input
      return false;
    }
  });
  $("#userphone").autocomplete({
    source: function (request, response) {

      $.ajax({
        url: "/ajax/transfer/get2.php",
        type: 'post',
        dataType: "json",
        data: {
          search: request.term
        },
        success: function (data) {
          response(data);
        }
      });
    },
    select: function (event, ui) {
      $('#username').val(ui.item.name); // display the selected text
      $('#userphone').val(ui.item.phone); // save selected id to input
      return false;
    }
  });

});
        var mangso = ['không', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín'];

function dochangchuc(so, daydu) {
  var chuoi = "";
  chuc = Math.floor(so / 10);
  donvi = so % 10;
  if (chuc > 1) {
    chuoi = " " + mangso[chuc] + " mươi";
    if (donvi == 1) {
      chuoi += " mốt";
    }
  } else if (chuc == 1) {
    chuoi = " mười";
    if (donvi == 1) {
      chuoi += " một";
    }
  } else if (daydu && donvi > 0) {
    chuoi = " lẻ";
  }
  if (donvi == 5 && chuc > 1) {
    chuoi += " lăm";
  } else if (donvi > 1 || (donvi == 1 && chuc == 0)) {
    chuoi += " " + mangso[donvi];
  }
  return chuoi;
}

function docblock(so, daydu) {
  var chuoi = "";
  tram = Math.floor(so / 100);
  so = so % 100;
  if (daydu || tram > 0) {
    chuoi = " " + mangso[tram] + " trăm";
    chuoi += dochangchuc(so, true);
  } else {
    chuoi = dochangchuc(so, false);
  }
  return chuoi;
}

function dochangtrieu(so, daydu) {
  var chuoi = "";
  trieu = Math.floor(so / 1000000);
  so = so % 1000000;
  if (trieu > 0) {
    chuoi = docblock(trieu, daydu) + " triệu";
    daydu = true;
  }
  nghin = Math.floor(so / 1000);
  so = so % 1000;
  if (nghin > 0) {
    chuoi += docblock(nghin, daydu) + " nghìn";
    daydu = true;
  }
  if (so > 0) {
    chuoi += docblock(so, daydu);
  }
  return chuoi;
}

function docso(so) {
  if (so == 0) return mangso[0];
  var chuoi = "",
    hauto = "";
  do {
    ty = so % 1000000000;
    so = Math.floor(so / 1000000000);
    if (so > 0) {
      chuoi = dochangtrieu(ty, true) + hauto + chuoi;
    } else {
      chuoi = dochangtrieu(ty, false) + hauto + chuoi;
    }
    hauto = " tỷ";
  } while (so > 0);
  return chuoi;
}
function formatCurrency(number){

   number!=''?number= parseInt(number).toString():number='0';
    var n = number.split('').reverse().join("");
    var n2 = n.replace(/\d\d\d(?!$)/g, "$&,");    
    return  n2.split('').reverse().join('');
}
                                 $("#faddbalance").ajaxForm({
                  dataType : 'json',
                  url: '/ajax/addmoney.php',
                  beforeSubmit : function() {
                      $("#btn-congtien").html('Loading...');
                  },
                  success: function(data) {        
                      alert(data.msg);
                    $("#btn-congtien").html('<button type="submit" class="btn login-button"><i class="fa fa-arrow-right"></i> Chuyển tiền</button>');
                    if (data.code==0) {
                      $("#faddbalance")[0].reset();
                    }
                  }
            });
                                             $("#fadduser").ajaxForm({
                  dataType : 'json',
                  url: '/ajax/adduser.php',
                  beforeSubmit : function() {
                      $("#btn-addacc").html('Loading...');
                  },
                  success: function(data) {        
                      alert(data.msg);
                      $("#btn-addacc").html('<button type="submit" class="btn btn-primary">Tạo mới</button>');
                      if (data.code==0) {
                        load(1);
                      }
                  }
            });
                                             
                                 $("#money").keyup(function() {
  var toword = docso($(this).val()).trim();
$("#toword").html(toword.charAt(0).toUpperCase() + toword.slice(1)+' đồng [<strong>'+formatCurrency($(this).val())+'</strong>đ]');

});