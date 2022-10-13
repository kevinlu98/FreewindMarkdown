# Typecho编辑器插件FreewindMarkdown

## 介绍

> **插件名称**：Freewind Markdown
>
> **作者：** Mr丶冷文
>
> **版本：** 1.0
>
> **地址：** [https://github.com/kevinlu98/FreewindMarkdown](https://github.com/kevinlu98/FreewindMarkdown)
>
> **描述信息：** 新版`Freewind`主题的`Markdown`编辑器，当然也可以单独使用，支持`Markdown`、`TeX(KaTeX) `数据公式、时序图、流程图，此外我还定义了一些短代码，大家有兴趣可以下载下来看看

之前[Freewind主题V1.4](https://www.kevinlu98.cn/archives/103.html)的编辑器有些BUG，在文章编辑页右侧的样式会有些乱，而且有些人希望可以关闭编辑器，因为他们想用其它的编辑器插件，所以我就把编辑器做成了插件，不过这次的编辑器是基于成品项目[Editor.md](http://editor.md.ipandao.com/)开发的，不像之前用[CodeMirror](http://codemirror.net/)做的，基于成品项目做二次开发肯定比我之前做的好用，不过此编辑器不支持`Freewind主题V1.4`

## 功能说明

### 编辑器工具栏介绍

可以看到我自定义了一些工具，这些工具就是我们的短代码，这些其实就是之前我们`Freewind主题V1.4`里面支持的那些

![https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/dcbb09ae6ab34f93b5284f04555bc8e7.png](https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/dcbb09ae6ab34f93b5284f04555bc8e7.png)

### 短代码

在[Editor.md](http://editor.md.ipandao.com/)的基础上做了一些自定义解析，直接在编辑时就能预览短代码我效果

![https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/4939e68d89354383985a1737cc25de40.png](https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/4939e68d89354383985a1737cc25de40.png)

![https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/061cd855a2ea4545bca225beb263de74.png](https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/061cd855a2ea4545bca225beb263de74.png)

![https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/28f582f991344bc98b118d7db8ed790b.png](https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/28f582f991344bc98b118d7db8ed790b.png)

![https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/2e40c9dabc494828a89f6da508fcccd1.png](https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/2e40c9dabc494828a89f6da508fcccd1.png)

## TeX(KaTeX)公式、时序图及流程图

这里的功能本身`Editor.md`是支持的，我在插件里在用户的博文展示页做了渲染，也就是发布之后的文章依然也能解析这些，我这里用默认主题给大家展示

![https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/a626dd8b1bcc48de97c384919d34e2ea.png](https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/a626dd8b1bcc48de97c384919d34e2ea.png)

![https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/7d7437970ff646e88eb4e8b8c67199de.png](https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/7d7437970ff646e88eb4e8b8c67199de.png)

![https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/d22790107c3749f28a3da5f28ce50350.png](https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/d22790107c3749f28a3da5f28ce50350.png)

## 安装

- 将下载的插件改名为`FreewindMarkdown`
- 上传插件到`站点根目录/usr/plugins/`目录下
- 在 控制台->插件里面找到并开启插件

![https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/f0e5d12a390e4bc3a45cb3e0398179f1.png](https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/f0e5d12a390e4bc3a45cb3e0398179f1.png)

- 开启后点设置进入设置页面，根据自己需要和主题的情况选择功能，保存即可

![https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/0da8e5113c6644ea8fe0cd6ec650802e.png](https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/2046/0da8e5113c6644ea8fe0cd6ec650802e.png)