## QPlayer-Typecho-Plugin
一款简洁小巧的Typecho底部悬浮音乐播放器插件. 支持网易云音乐解析。

## 更新
2020-05-02 Jesus0s修复网易云解析接口

## 使用方法
Download ZIP, 解压，将 QPlayer-Typecho-Plugin-master 重命名为 QPlayer ，之后上传到你博客中的 /usr/plugins 目录，在后台启用即可

插件后台配置歌曲格式: 

{title:"xxx", artist:"xxx", cover:"http:xxxx", mp3:"http:xxxx"} ，每个歌曲之间用英文,隔开。
**请保证歌曲列表里至少有一首歌**！

## 添加网易云音乐(需主机支持curl扩展)
去网易云音乐官网找到音乐id,支持 **歌单|艺人|专辑|单曲** 类型的id解析**(单曲解析需国内服务器)**，billboard等排行榜也支持(实质就是歌单)。

更多详细说明请参见插件后台。


建议网站加入PJAX来实现跨页面播放音乐

- [PJAX 实现无刷新加载网页][3]

- [一句代码为typecho博客加上pjax技术][4]



 [3]: https://32mb.space/archives/7.html
 [4]: http://www.ihewro.com/archives/354/
