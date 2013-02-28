<?php /* Smarty version Smarty-3.1.13, created on 2013-02-27 12:51:26
         compiled from "album-create.tpl" */ ?>
<?php /*%%SmartyHeaderCode:717180421512beaca7251a2-37132186%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f7228f73a8bc50793af28d577b3c05eb369f37f3' => 
    array (
      0 => 'album-create.tpl',
      1 => 1361945712,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '717180421512beaca7251a2-37132186',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_512beaca7a1ee1_06225171',
  'variables' => 
  array (
    'imageURL' => 0,
    'albumName' => 0,
    'dID' => 0,
    'artistName' => 0,
    'released' => 0,
    'genre' => 0,
    'g' => 0,
    'avgRating' => 0,
    'tags' => 0,
    'tag' => 0,
    'tracks' => 0,
    'track' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_512beaca7a1ee1_06225171')) {function content_512beaca7a1ee1_06225171($_smarty_tpl) {?><!-- album display -->
<div class="full-album-listing">
    <img width="150px" height="150px" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['imageURL']->value, ENT_QUOTES, 'ISO-8859-1', true);?>
"/>
    <h1><?php echo $_smarty_tpl->tpl_vars['albumName']->value;?>
</h1>
    <form action='/~celaya/riptideMusic/api/found/<?php echo $_smarty_tpl->tpl_vars['dID']->value;?>
' method='POST'>
    <button  value='<?php echo $_smarty_tpl->tpl_vars['dID']->value;?>
' type='submit'>
        <i class='icon-plus'></i><i class='icon-pencil'></i> Be a founding writer!
    </button>
    </form>
    <a href="/~celaya/riptideMusic/artist.php?name=<?php echo rawurlencode($_smarty_tpl->tpl_vars['artistName']->value);?>
">
        <p class="artistName"><?php echo $_smarty_tpl->tpl_vars['artistName']->value;?>
</p>
    </a>
    <p><?php echo $_smarty_tpl->tpl_vars['released']->value;?>
</p>
    <?php  $_smarty_tpl->tpl_vars['g'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['g']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['genre']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['g']->key => $_smarty_tpl->tpl_vars['g']->value){
$_smarty_tpl->tpl_vars['g']->_loop = true;
?>
        <a style="margin-right:0.2em" href="genre/<?php echo $_smarty_tpl->tpl_vars['g']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['g']->value;?>
</a>
    <?php } ?>
    <p><?php echo $_smarty_tpl->tpl_vars['avgRating']->value;?>
</p>
    <div class="tags">
        <?php  $_smarty_tpl->tpl_vars['tag'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tag']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tags']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tag']->key => $_smarty_tpl->tpl_vars['tag']->value){
$_smarty_tpl->tpl_vars['tag']->_loop = true;
?>
            <a href="/~celaya/riptideMusic/tag.php?searchTags=<?php echo $_smarty_tpl->tpl_vars['tag']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['tag']->value;?>
</a>
        <?php } ?>
    </div>
    <table>
        <?php  $_smarty_tpl->tpl_vars['track'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['track']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tracks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['track']->key => $_smarty_tpl->tpl_vars['track']->value){
$_smarty_tpl->tpl_vars['track']->_loop = true;
?>
            <tr>
                <td><?php echo $_smarty_tpl->tpl_vars['track']->value[1];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['track']->value[2];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['track']->value[0];?>
</td>
            </tr>
        <?php } ?>
    </table>
    <hr>
</div>
<?php }} ?>