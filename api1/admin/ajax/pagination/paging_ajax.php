<?php

class paging_ajax
{
    public $data; // DATA
    public $per_page = 5; // SỐ RECORD TRÊN 1 TRANG
    public $page; // SỐ PAGE 
    public $text_sql; // CÂU TRUY VẤN
    
    //	THÔNG SỐ SHOW HAY HIDE 
    public $show_pagination = true;
    public $show_goto = false;
    public $show_total = true;
    
    // TÊN CÁC CLASS
    public $class_pagination; 
    public $class_active;
    public $class_inactive;
    public $class_go_button;
    public $class_text_total;
    public $class_txt_goto;    
    
    private $cur_page;	// PAGE HIỆN TẠI
    public $num_row; // SỐ RECORD
    
    // PHƯƠNG THỨC LẤY KẾT QUẢ CỦA TRANG 
    public function GetResult($model)
    {

        $classname = 'Models_' . $model;
$model = new $classname();

        
        // TÌNH TOÁN THÔNG SỐ LẤY KẾT QUẢ
        $this->cur_page = $this->page;
        $this->page -= 1;
        $this->per_page = $this->per_page;
        $start = $this->page * $this->per_page;
        
        // TÍNH TỔNG RECORD TRONG BẢNG

		$result = $model->customQuery($this->text_sql);
        $this->num_row = count($result);
        
        // LẤY KẾT QUẢ TRANG HIỆN TẠI
        return $model->customQuery($this->text_sql." LIMIT $start, $this->per_page");
    }
    
    // PHƯƠNG THỨC XỬ LÝ KẾT QUẢ VÀ HIỂN THỊ PHÂN TRANG
    public function Load()
    {

             /* <ul class="pagination">
         <li  ><a class='page-link'>‹</a></li>
         <li class="active"><a class='page-link'>1</a></li>
         <li><a class='page-link'>2</a></li>
        <li><a class='page-link'>3</a></li>
        <li><a class='page-link'>4</a></li>
        <li><a class='page-link'>5</a></li>
        <li><a class='page-link'>6</a></li>
        <li><a class='page-link'>7</a></li>
        <li><a class='page-link'>...</a></li>
        <li><a class='page-link'>26</a></li>


  
         <li><a class='page-link'>s</a></li>
 
      </ul>*/




        // KHÔNG PHÂN TRANG THÌ TRẢ KẾT QUẢ VỀ
        if(!$this->show_pagination)
            return "";
        /*return $this->data;*/
        
        // SHOW CÁC NÚT NEXT, PREVIOUS, FIRST & LAST
        $previous_btn = true;
        $next_btn = true;
        $first_btn = true;
        $last_btn = true;    
        
        // GÁN DATA CHO CHUỖI KẾT QUẢ TRẢ VỀ 
 /*       $msg = $this->data;*/
        
        // TÍNH SỐ TRANG
        $count = $this->num_row;
        $no_of_paginations = ceil($count / $this->per_page);
        
        // TÍNH TOÁN GIÁ TRỊ BẮT ĐẦU & KẾT THÚC VÒNG LẶP
        if ($this->cur_page >= 7) {
            $start_loop = $this->cur_page - 3;
            if ($no_of_paginations > $this->cur_page + 3)
                $end_loop = $this->cur_page + 3;
            else if ($this->cur_page <= $no_of_paginations && $this->cur_page > $no_of_paginations - 6) {
                $start_loop = $no_of_paginations - 6;
                $end_loop = $no_of_paginations;
            } else {
                $end_loop = $no_of_paginations;
            }
        } else {
            $start_loop = 1;
            if ($no_of_paginations > 7)
                $end_loop = 7;
            else
                $end_loop = $no_of_paginations;
        }
        
        // NỐI THÊM VÀO CHUỖI KẾT QUẢ & HIỂN THỊ NÚT FIRST 
        $msg = "<ul class='$this->class_pagination'>";
        if ($first_btn && $this->cur_page > 1) {
            $msg .= "<li onclick='load(1)' class='page-item'><a class='page-link'>Đầu</a></li>";
        }/* else if ($first_btn) {
            $msg .= "<li onclick='load(1' class='$this->class_inactive'><a class='page-link'>Đầu</a></li>";
        }*/
    
        // HIỂN THỊ NÚT PREVIOUS
        if ($previous_btn && $this->cur_page > 1) {
            $pre = $this->cur_page - 1;
            $msg .= "<li onclick='load($pre)' class='page-item'><a class='page-link'>‹</a></li>";
        } elseif ($previous_btn) {
            $msg .= "<li class='page-item disabled'><a class='page-link'>‹</a></li>";
        }
        for ($i = $start_loop; $i <= $end_loop; $i++) {
        
            if ($this->cur_page == $i)
                $msg .= "<li onclick='load($i)' class='active'><a class='page-link'>{$i}</a></li>";
            else
                $msg .= "<li onclick='load($i)' class='page-item'><a class='page-link'>{$i}</a></li>";
        }

        // HIỂN THỊ NÚT NEXT
        if ($next_btn && $this->cur_page < $no_of_paginations) {
            $nex = $this->cur_page + 1;
            $msg .= "<li onclick='load($nex)'><a class='page-link'>›</a></li>";
        } else if ($next_btn) {
            $msg .= "<li class='disabled'><a class='page-link'>›</a></li>";
        }
        
        // HIỂN THỊ NÚT LAST
        if ($last_btn && $this->cur_page < $no_of_paginations) {
            $msg .= "<li onclick='load($no_of_paginations)' class='page-item'><a class='page-link'>Cuối</a></li>";
        } else if ($last_btn) {
            $msg .= "<li onclick='load($no_of_paginations)' class='page-item'><a class='page-link'>Cuối</a></li>";
        }
        
        // SHOW TEXTBOX ĐỂ NHẬP PAGE KO ? 
        if($this->show_goto)
            $goto = "<input type='text' id='goto' class='$this->class_txt_goto' size='1' style='margin-top:-1px;margin-left:40px;margin-right:10px'/><input type='button' id='go_btn' class='$this->class_go_button' value='Đến'/>";
        if($this->show_total)
            $total_string = "<span id='total' class='$this->class_text_total' a='$no_of_paginations'>Trang <b>" . $this->cur_page . "</b>/<b>$no_of_paginations</b></span>";
    /*    $stradd =  $goto . $total_string;*/
        // TRẢ KẾT QUẢ TRỞ VỀ
        return array('pagination'=>$msg,'count'=>$no_of_paginations)  ;  // Content for pagination
    }     
            
}

?>