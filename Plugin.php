<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * Typecho 底部悬浮音乐播放器
 *
 * @package QPlayer
 * @author Jrohy,MoLeft,Jesus0s
 * @version 1.4.1
 * @link https://www.jesus0s.com
 */
class QPlayer_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        Typecho_Plugin::factory('Widget_Archive')->header = array('QPlayer_Plugin', 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array('QPlayer_Plugin', 'footer');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
   
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form) {

        $autoPlay = new Typecho_Widget_Helper_Form_Element_Radio(
        'autoPlay', array('0'=> '关闭', '1'=> '开启'), 0, '自动播放',
            '');
        $form->addInput($autoPlay);

        $rotate = new Typecho_Widget_Helper_Form_Element_Radio(
        'rotate', array('0'=> '关闭', '1'=> '开启'), 0, '封面旋转',
            '');
        $form->addInput($rotate);

        $color = new Typecho_Widget_Helper_Form_Element_Text('color', NULL, '', _t('自定义主色调'), _t('默认为<span style="color: #1abc9c;">#1abc9c</span>, 你可以自定义任何你喜欢的颜色作为播放器主色调。自定义主色调支持css的设置格式，如: `#233333`,"rgb(255,255,255)","rgba(255,255,255,1)","hsl(0, 0%, 100%)","hsla(0, 0%, 100%,1)"。填写其他错误的格式可能不会生效。'));
        $form->addInput($color);

        $css = new Typecho_Widget_Helper_Form_Element_Textarea('css', NULL, '', _t('自定义CSS'),'');
        $form->addInput($css);

        $js = new Typecho_Widget_Helper_Form_Element_Textarea('js', NULL, 
'//改变列表的背景颜色(错开颜色)
function bgChange() {
	var lis= $(".lib");
	for(var i=0; i<lis.length; i+=2)
	lis[i].style.background = "rgba(246, 246, 246, 0.5)";
}
bgChange();
', _t('自定义JS'),'');
        $form->addInput($js);

        $musicList = new Typecho_Widget_Helper_Form_Element_Textarea('musicList', NULL, 
'{title:"感谢你曾来过",artist:"周思涵",mp3:"460578140",cover:"https://p1.music.126.net/iL36G-e6rm2zwCUmlL4pOw==/109951162859100521.jpg?param=106x106",},
{title:"“据说真的有神”（Prod.蛋卷）",artist:"木秦",mp3:"521955367",cover:"https://p1.music.126.net/wDf7j_zUmCRQ3Iri4usJ4A==/109951163074572687.jpg?param=106x106",},
{title:"如果这都不算爱",artist:"张学友",mp3:"188432",cover:"https://p1.music.126.net/blZlhdVmYSUwgqSUxFs88Q==/19047939439853431.jpg?param=106x106",},
',_t('歌曲列表'), _t('格式: {title:"xxx", artist:"xxx", cover:"http:xxxx", mp3:"http:xxxx"} ，每个歌曲之间用英文,隔开。请保证歌曲列表里至少有一首歌！<br><h4 style="margin-bottom:5px;margin-top:12px;">添加网易云音乐(需主机支持curl扩展)</h4><div style="background-color:#467b96;padding:5px 10px;max-width:109px;border-radius: 2px;"><a href="'.Helper::options()->pluginUrl.'/QPlayer/IDExplain.php" target="_blank" style="font-size:14px;color:#fff;outline:none;text-decoration:none;">网易云音乐id解析</a>
        	</div><p style="font-size: .92857em;color: #999; margin-top: 4px; margin-bottom:15px;">请自行去网易云音乐网页版获取音乐id(具体在每个音乐项目的网址最后会有个id)。<b style="color: #888;">将解析出的音乐链接复制到上面歌曲列表里(注意检查与现有歌曲是否用英文,隔开)。有版权的音乐无法解析!</b></p>'));
        $form->addInput($musicList);
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 输出头部css
     * 
     * @access public
     * @return void
     */
    public static function header() {
        $cssUrl = Helper::options()->pluginUrl . '/QPlayer/css/player.css';
        echo '<link rel="stylesheet" href="' . $cssUrl . '">';
    }
    /**
     * 输出底部
     * 
     * @access public
     * @return void
     */
    public static function footer() {
        $options = Typecho_Widget::widget('Widget_Options')->plugin('QPlayer'); 
		echo '
			<div id="QPlayer" style="z-index:2016">
			<div id="pContent">
				<div id="player">
					<span class="cover"></span>
					<div class="ctrl">
						<div class="musicTag marquee">
							<strong>Title</strong>
							 <span> - </span>
							<span class="artist">Artist</span>
						</div>
						<div class="progress">
							<div class="timer left">0:00</div>
							<div class="contr">
								<div class="rewind icon"></div>
								<div class="playback icon"></div>
								<div class="fastforward icon"></div>
							</div>
							<div class="right">
								<div class="liebiao icon"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="ssBtn">
				        <div class="adf"></div>
			    </div>
			</div>
			<ol id="playlist"></ol>
			</div>
             ';
        if($options->color != '') {
            echo '<style>
            #pContent .ssBtn {
                background-color:'.$options->color.';
            }
            #playlist li.playing, #playlist li:hover{
                border-left-color:'.$options->color.';
            }
            </style>';
        }
        if($options->css != '') {
            echo '<style>'.$options->css.'</style>' . "\n";
        }
        echo "
            <script>
                if (typeof jQuery == 'undefined') {
                    var script=document.createElement('script'); 
                    script.type='text/javascript'; 
                    script.src='". Helper::options()->pluginUrl . "/QPlayer/js/jquery.min.js';
                    document.getElementsByTagName('head')[0].appendChild(script);
                }
            </script>
        ";
        echo '
            <script>
              var autoplay = '.$options->autoPlay.';
              var playlist = [
              '.$options->musicList.'
              ];
              var isRotate = '.$options->rotate.';
            </script> ' . "\n";
        echo '<script src="'.Helper::options()->pluginUrl . '/QPlayer/js/jquery.marquee.min.js"></script>' . "\n";
        echo '<script src="'.Helper::options()->pluginUrl . '/QPlayer/js/player.js"></script>' . "\n";
        if ($options->js != '') {
            echo '<script>'.$options->js.'</script>' . "\n";
        }
    }

}
