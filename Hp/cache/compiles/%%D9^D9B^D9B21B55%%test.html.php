<?php /* Smarty version 2.6.25, created on 2011-02-22 11:28:47
         compiled from test.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
 <HEAD>
  <TITLE> New Document </TITLE>
  <META NAME="Generator" CONTENT="EditPlus">
  <META NAME="Author" CONTENT="">
  <META NAME="Keywords" CONTENT="">
  <META NAME="Description" CONTENT="">
 </HEAD>

 <BODY>
  <?php $_from = $this->_tpl_vars['value']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['test1'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['test1']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['test1']['iteration']++;
?>
  <?php echo $this->_tpl_vars['item']; ?>

 <?php endforeach; endif; unset($_from); ?>
 </BODY>
</HTML>