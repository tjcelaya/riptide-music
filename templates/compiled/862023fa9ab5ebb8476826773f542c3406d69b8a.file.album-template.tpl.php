<?php /* Smarty version Smarty-3.1.13, created on 2013-03-04 12:16:39
         compiled from "album-template.tpl" */ ?>
<?php /*%%SmartyHeaderCode:442836463511b08512a46d1-90512686%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '862023fa9ab5ebb8476826773f542c3406d69b8a' => 
    array (
      0 => 'album-template.tpl',
      1 => 1362417370,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '442836463511b08512a46d1-90512686',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_511b0851313a21_73440346',
  'variables' => 
  array (
    'artistName' => 0,
    'albumName' => 0,
    'released' => 0,
    'genres' => 0,
    'g' => 0,
    'avgRating' => 0,
    'tags' => 0,
    'tag' => 0,
    'tracklist' => 0,
    'track' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_511b0851313a21_73440346')) {function content_511b0851313a21_73440346($_smarty_tpl) {?><!-- album display -->
<div class="full-album-listing">
    <img width="150" height="150" src="img/<?php echo rawurlencode($_smarty_tpl->tpl_vars['artistName']->value);?>
%20-%20<?php echo rawurlencode($_smarty_tpl->tpl_vars['albumName']->value);?>
(<?php echo $_smarty_tpl->tpl_vars['released']->value;?>
).jpg"/>
    <a href="album.php?name=<?php echo rawurlencode($_smarty_tpl->tpl_vars['albumName']->value);?>
&artist=<?php echo rawurlencode($_smarty_tpl->tpl_vars['artistName']->value);?>
">
        <h1><?php echo $_smarty_tpl->tpl_vars['albumName']->value;?>
</h1>
    </a>
    <a href="artist.php?name=<?php echo rawurlencode($_smarty_tpl->tpl_vars['artistName']->value);?>
">
        <p class="artistName"><?php echo $_smarty_tpl->tpl_vars['artistName']->value;?>
</p>
    </a>
    <p><?php echo $_smarty_tpl->tpl_vars['released']->value;?>
</p>
    <?php  $_smarty_tpl->tpl_vars['g'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['g']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['genres']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
            <a href="tag.php?searchTags=<?php echo $_smarty_tpl->tpl_vars['tag']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['tag']->value;?>
</a>
        <?php } ?>
    </div>
    <table>
        <?php  $_smarty_tpl->tpl_vars['track'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['track']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tracklist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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