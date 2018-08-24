   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="http://123.17.153.33:30000/socket.io/socket.io.js"></script>
<?php
class Controllers_AutoCards extends Controllers_Core {
    
    function __construct($entity) {
        $this->entity = $entity;
        parent::__construct();
    }
    function save() {
        foreach($_POST as $key => $value) {
            $$key = $value;
        }
    
        //  set object
        $persistents = $this->getPersistents();
        $persistents->setId($id);
        $persistents->setTime($time);
        $persistents->setOrders($orders);
        $persistents->setStatus($status);
       ?>

   <script>
        $(function() {
            var socket = io.connect('http://123.17.153.33:30000');
            socket.on('connect', function() {
                $("#data").html('Socket connected');
            });
            
            <?php if ($status==1): ?>

     socket.emit('on');
     <?php else: ?>
        socket.emit('off');
<?php endif ?>
            
        });
    </script>

       <?php
                
        if($persistents->isValidate()) {
            // models wrapper
            $models = $this->getModels();
            $models->setPersistents($persistents);
            $affected_rows = 0;
            if($id) {
                $affected_rows = $models->edit();
            }
            else {
                $affected_rows = $models->add();
            }
            
            
            // unwrapper object
            unset($_SESSION['object_wrapper']);
            $this->setRedirect($this->list_file3);   
        }
        else {
            
            if($id) {
                $this->setRedirect($this->add_file);
            }
            else {
                $this->setRedirect($this->add_file);
            }
        }
    }
    
    
    
}