<?xml version="1.0"?>
<xsl:stylesheet
	xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:gui="uri:In2iGui"
	xmlns:html="http://www.w3.org/1999/xhtml"
    version="1.0"
    exclude-result-prefixes="gui"
    >

<!--xsl:include href="iphone.xsl"/-->
<xsl:include href="layout.xsl"/>
<xsl:include href="formula.xsl"/>
<!--xsl:include href="view.xsl"/--> <!-- TODO: remove this -->
<xsl:include href="toolbar.xsl"/>
<xsl:include href="links.xsl"/>
<xsl:include href="other.xsl"/>

<xsl:output encoding="UTF-8" omit-xml-declaration="yes" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>

<xsl:template match="gui:gui">
<html class="in2igui">

<head>
	<xsl:if test="$profile='true'">
	<script>
		console.profile();
		window.setTimeout(function() {console.profileEnd()},5000);
	</script>
	</xsl:if>
<title><xsl:value-of select="@title"/></title>
	<meta http-equiv="X-UA-Compatible" content="IE8" />
<xsl:choose>
	<xsl:when test="$dev='true'">
		<link rel="stylesheet" href="{$context}/In2iGui/css/dev.css?version={$version}" type="text/css" media="screen" title="no title" charset="utf-8"/>
	</xsl:when>
	<xsl:otherwise>
		<link rel="stylesheet" href="{$context}/In2iGui/bin/minimized.css?version={$version}" type="text/css" media="screen" title="no title" charset="utf-8"/>
	</xsl:otherwise>
</xsl:choose>
<!--
<xsl:comment><![CDATA[[if lt IE 8]>
	<script src="]]><xsl:value-of select="$context"/><![CDATA[/In2iGui/lib/IE8.js" type="text/javascript"></script>
<![endif]]]></xsl:comment>-->
<xsl:comment><![CDATA[[if IE 8]>
	<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$context"/><![CDATA[/In2iGui/css/msie8.css?version=]]><xsl:value-of select="$version"/><![CDATA["> </link>
<![endif]]]></xsl:comment>
<xsl:comment><![CDATA[[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$context"/><![CDATA[/In2iGui/css/msie6.css?version=]]><xsl:value-of select="$version"/><![CDATA["> </link>
<![endif]]]></xsl:comment>
<xsl:comment><![CDATA[[if IE 7]>
	<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$context"/><![CDATA[/In2iGui/css/msie7.css?version=]]><xsl:value-of select="$version"/><![CDATA["> </link>
<![endif]]]></xsl:comment>
<xsl:for-each select="//gui:css">
	<link rel="stylesheet" href="{@url}" type="text/css" media="screen" title="no title" charset="utf-8"/>
</xsl:for-each>
<xsl:choose>
	<xsl:when test="$dev='true'">
		<script src="{$context}/In2iGui/lib/swfupload/swfupload.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/lib/n2i.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/lib/In2iScripts/In2iDate.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/lib/json2.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/In2iGui.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Source.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/DragDrop.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Window.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Formula.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/List.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Tabs.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/ObjectList.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Alert.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Button.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Selection.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Toolbar.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/ImagePicker.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/BoundPanel.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/RichText.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Picker.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/ImageViewer.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/ColorPicker.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Upload.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/ProgressBar.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Gallery.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Calendar.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Layout.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Dock.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Box.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Wizard.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Articles.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/TextField.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/SearchField.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Overflow.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Fragment.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Bar.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/IFrame.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Segmented.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Flash.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Overlay.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Links.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<script src="{$context}/In2iGui/js/Link.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
	</xsl:when>
	<xsl:otherwise>
		<script src="{$context}/In2iGui/bin/minimized.noproto.js?version={$version}" type="text/javascript" charset="utf-8"><xsl:comment/></script>
	</xsl:otherwise>
</xsl:choose>
<xsl:if test="//gui:graphviz">
	<script src="{$context}/In2iGui/ext/Graphviz.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
</xsl:if>
<xsl:if test="//gui:graph">
	<link rel="stylesheet" href="{$context}/In2iGui/ext/graph.css?version={$version}" type="text/css" media="screen" title="no title" charset="utf-8"/>
	<script type="text/javascript" src="{$context}/In2iGui/lib/protovis-3.2/protovis-r3.2.js"><xsl:comment/></script>
	<script type="text/javascript" src="{$context}/In2iGui/lib/raphael-min.js"><xsl:comment/></script>
	<script src="{$context}/In2iGui/ext/Graph.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
</xsl:if>
<xsl:if test="//gui:chart">
	<script src="{$context}/In2iGui/lib/swfobject.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
	<script src="{$context}/In2iGui/ext/FlashChart.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
</xsl:if>
<xsl:for-each select="gui:localize[@source]">
	<script src="{@source}" type="text/javascript" charset="utf-8"><xsl:comment/></script>
</xsl:for-each>
<xsl:for-each select="gui:controller[@source]">
	<script src="{@source}" type="text/javascript" charset="utf-8"><xsl:comment/></script>
</xsl:for-each>
<script type="text/javascript">
<xsl:if test="@state">
In2iGui.state = '<xsl:value-of select="@state"/>';
</xsl:if>
In2iGui.context = '<xsl:value-of select="$context"/>';
<xsl:for-each select="gui:controller[@source]">
	<xsl:if test="@name">
	if (window['<xsl:value-of select="@name"/>']!==undefined) {
		In2iGui.listen(<xsl:value-of select="@name"/>);
	}
	</xsl:if>
</xsl:for-each>
</script>
<xsl:call-template name="dwr-setup"/>
</head>
<body class="in2igui">
	<xsl:choose>
		<xsl:when test="@padding"><div style="padding: {@padding}px;" class="in2igui_body"><xsl:apply-templates/></div></xsl:when>
		<xsl:otherwise><xsl:apply-templates/></xsl:otherwise>
	</xsl:choose>
</body>
</html>
</xsl:template>

<xsl:template name="dwr-setup">
	<xsl:if test="gui:dwr">
		<script src="{$context}{gui:dwr/@base}engine.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<xsl:for-each select="gui:dwr/gui:interface">
			<script src="{$context}{../@base}interface/{@name}.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		</xsl:for-each>
		<script type="text/javascript">
			dwr.engine.setErrorHandler(In2iGui.dwrErrorHandler);
		</script>
	</xsl:if>
</xsl:template>

<xsl:template name="gui:createobject">
	<xsl:if test="@name and @name!=''">
		if (window['<xsl:value-of select="@name"/>']===undefined) {
			window['<xsl:value-of select="@name"/>'] = <xsl:value-of select="generate-id()"/>_obj;
		}
	</xsl:if>
</xsl:template>

<xsl:template name="gui:escapeScript">
	<xsl:param name="text"/>
	<xsl:choose>
		<xsl:when test='contains($text,"&apos;")'>
			<xsl:value-of select='substring-before($text,"&apos;")'/>
			<xsl:value-of select='"\&apos;"'/>
			<xsl:call-template name="gui:escapeScript">
				<xsl:with-param name="text" select='substring-after($text,"&apos;")'/>
			</xsl:call-template>
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="$text"/>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template match="gui:source">
<script type="text/javascript">
	var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Source({name:'<xsl:value-of select="@name"/>'
		<xsl:choose>
			<xsl:when test="@url">,url:'<xsl:value-of select="@url"/>'</xsl:when>
			<xsl:when test="@dwr">,dwr:'<xsl:value-of select="@dwr"/>'</xsl:when>
		</xsl:choose>
		<xsl:if test="@lazy='true'">,lazy:true</xsl:if>
	});
	<xsl:call-template name="gui:createobject"/>
	with (<xsl:value-of select="generate-id()"/>_obj) {
		<xsl:for-each select="gui:parameter">
			addParameter({key:'<xsl:value-of select="@key"/>',value:'<xsl:value-of select="@value"/>'})
		</xsl:for-each>
	}
</script>
</xsl:template>

<xsl:template name="gui:subgui">
	<div>
	<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="gui:script">
<script type="text/javascript">
	<xsl:apply-templates/>
</script>
</xsl:template>

<xsl:template match="gui:listen">
<script type="text/javascript">
	(function() {
		var listener = {};
		<xsl:for-each select="*">
			listener['$<xsl:value-of select="local-name()"/>$<xsl:value-of select="../@for"/>']=function() {<xsl:apply-templates/>};
		</xsl:for-each>
		In2iGui.listen(listener);
	
	})()
</script>	
</xsl:template>

<xsl:template match="gui:dock">
<table class="in2igui_dock" id="{generate-id()}">
	<xsl:if test="@position='top' or not(@position)">
		<thead>
			<tr><td>
				<xsl:apply-templates/>
			</td></tr>
		</thead>
	</xsl:if>
	<xsl:if test="@position='bottom'">
		<tfoot>
			<xsl:if test="gui:tabs"><xsl:attribute name="class">in2igui_dock_tabs</xsl:attribute></xsl:if>
			<tr><td>
				<xsl:apply-templates/>
			</td></tr>
		</tfoot>
	</xsl:if>
	<tbody>
		<tr><td>
			<div class="in2igui_dock_progress"><xsl:comment/></div>
			<iframe src="{@url}" frameborder="0" name="{@frame-name}"/>
		</td></tr>
	</tbody>
</table>
<script type="text/javascript">
	var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Dock({element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>'<xsl:if test="gui:tabs">,tabs:true</xsl:if>});
	<xsl:call-template name="gui:createobject"/>
</script>
</xsl:template>

<xsl:template match="gui:frames">
	<html>
		<head>
		</head>
		<frameset rows="84,*" framespacing="0" frameborder="0" border="0">
			<xsl:for-each select="gui:frame">
				<frame noresize="noresize" src="{@source}" name="{@name}" frameborder="0" marginheight="0" marginwidth="0" border="0">
					<xsl:attribute name="scrolling">
						<xsl:choose>
							<xsl:when test="@scrolling='false'">no</xsl:when>
							<xsl:otherwise>auto</xsl:otherwise>
						</xsl:choose>
					</xsl:attribute>
				</frame>
			</xsl:for-each>
		</frameset>
	</html>
</xsl:template>

<xsl:template match="gui:iframe">
	<xsl:variable name="id">
		<xsl:choose>
			<xsl:when test="@id"><xsl:value-of select="@id"/></xsl:when>
			<xsl:otherwise><xsl:value-of select="generate-id()"/></xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<iframe id="{$id}" name="{$id}" src="{@source}" frameborder="0">
		<xsl:attribute name="style">
			<xsl:text>width: 100%; background: #fff; display: block;</xsl:text>
			<xsl:choose>
				<xsl:when test="@height"><xsl:text>height: </xsl:text><xsl:value-of select="@height"/><xsl:text>px;</xsl:text></xsl:when>
				<xsl:otherwise>height: 100%;</xsl:otherwise>
			</xsl:choose>
			<xsl:if test="@border='true'">
				<xsl:text>border: 1px solid #ddd; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;</xsl:text>
			</xsl:if>
		</xsl:attribute>
		<xsl:comment/>
	</iframe>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.IFrame({
			element:'<xsl:value-of select="$id"/>',
			name:'<xsl:value-of select="@name"/>'
			<xsl:if test="@state">
				,state:'<xsl:value-of select="@state"/>'
			</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>
	

<xsl:template match="gui:selection">
	<div class="in2igui_selection" id="{generate-id()}"><xsl:apply-templates/></div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Selection({
			element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>'
			<xsl:if test="@value">,value:'<xsl:value-of select="@value"/>'</xsl:if>
		});
		with (<xsl:value-of select="generate-id()"/>_obj) {
			<xsl:for-each select="gui:item">
				registerItem('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@title"/>','<xsl:value-of select="@icon"/>','<xsl:value-of select="@badge"/>','<xsl:value-of select="@value"/>','<xsl:value-of select="@kind"/>');
			</xsl:for-each>
			<xsl:for-each select="gui:source">
				registerSource(<xsl:value-of select="generate-id()"/>_obj);
			</xsl:for-each>
			<xsl:for-each select="gui:items">
				registerItems(<xsl:value-of select="generate-id()"/>_obj);
			</xsl:for-each>
		}
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:selection/gui:item">
	<div id="{generate-id()}">
		<xsl:attribute name="class">in2igui_selection_item<xsl:if test="@value=../@value"> in2igui_selected</xsl:if></xsl:attribute>
		<xsl:if test="@badge"><strong class="in2igui_selection_badge"><xsl:value-of select="@badge"/></strong></xsl:if>
		<xsl:if test="@icon">
			<span>
				<xsl:attribute name="style">background-image: url('<xsl:value-of select="$context"/>/In2iGui/icons/<xsl:value-of select="@icon"/>1.png');</xsl:attribute>
				<xsl:attribute name="class">in2igui_icon_1</xsl:attribute>
				<xsl:comment/>
			</span>
		</xsl:if>
		<span class="in2igui_selection_label">
		<xsl:value-of select="@title"/>
		</span>
	</div>
</xsl:template>

<xsl:template match="gui:selection/gui:items">
	<xsl:if test="@title">
		<div class="in2igui_selection_title" id="{generate-id()}_title" style="display: none;"><span><xsl:value-of select="@title"/></span></div>
	</xsl:if>
	<div id="{generate-id()}">
		<xsl:comment/>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Selection.Items({
			element:'<xsl:value-of select="generate-id()"/>'
			,name:'<xsl:value-of select="@name"/>'
			<xsl:if test="@source">,source:<xsl:value-of select="@source"/></xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:selection/gui:source">
	<div id="{generate-id()}">
		<xsl:comment/>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Selection.Source('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@name"/>',{url:'<xsl:value-of select="@url"/>'});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:selection/gui:title">
	<div class="in2igui_selection_title"><span><xsl:value-of select="."/></span></div>
</xsl:template>

<!--             List            -->

<xsl:template match="gui:list">
	<div class="in2igui_list" id="{generate-id()}">
		<div class="in2igui_list_progress"></div>
		<xsl:if test="@state and @state!=//gui:gui/@state">
			<xsl:attribute name="style">display:none</xsl:attribute>
		</xsl:if>
		<div class="in2igui_list_navigation">
			<div class="in2igui_list_selection window_page"><div><div class="window_page_body"><xsl:comment/></div></div></div>
			<span class="in2igui_list_count"><xsl:comment/></span>
		</div>
		<div class="in2igui_list_body">
			<table cellspacing="0" cellpadding="0">
				<thead>
					<tr>
					<xsl:apply-templates select="gui:column"/>
					</tr>
				</thead>
				<tbody><xsl:comment/></tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.List({element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>',url:'<xsl:value-of select="@url"/>',<xsl:if test="@source">source:<xsl:value-of select="@source"/>,</xsl:if>state:'<xsl:value-of select="@state"/>',windowSize:'<xsl:value-of select="gui:window/@size"/>'});
		with (<xsl:value-of select="generate-id()"/>_obj) {
			<xsl:for-each select="gui:column">
				registerColumn({key:'<xsl:value-of select="@key"/>',title:'<xsl:value-of select="@title"/>'});
			</xsl:for-each>
		}
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:list/gui:window"></xsl:template>

<xsl:template match="gui:column">
	<th>
		<xsl:if test="@width='min'"><xsl:attribute name="style">width: 1%;</xsl:attribute></xsl:if>
		<xsl:value-of select="@title"/>
	</th>
</xsl:template>

<!--                             Tabs                             -->

<xsl:template match="gui:tabs">
<div id="{generate-id()}" class="in2igui_tabs">
	<xsl:if test="@below='true'">
		<xsl:apply-templates select="gui:tab"/>
	</xsl:if>
	<div>
		<xsl:attribute name="class">
			<xsl:text>in2igui_tabs_bar</xsl:text>
			<xsl:choose>
				<xsl:when test="@small='true' and @below='true'">
					<xsl:text> in2igui_tabs_bar_small_below</xsl:text>
				</xsl:when>
				<xsl:when test="@small='true'">
					<xsl:text> in2igui_tabs_bar_small</xsl:text>
				</xsl:when>
			</xsl:choose>
			<xsl:if test="@centered='true'">
				<xsl:text> in2igui_tabs_bar_centered</xsl:text>
			</xsl:if>
		</xsl:attribute>
		<ul>
		<xsl:for-each select="gui:tab">
			<li id="{generate-id()}_tab">
				<xsl:if test="position()=1">
					<xsl:attribute name="class">in2igui_tabs_selected</xsl:attribute>
				</xsl:if>
				<a href="javascript:void(0)"><span><span><xsl:value-of select="@title"/></span></span></a>
			</li>
		</xsl:for-each>
		</ul>
	</div>
	<xsl:if test="not(@below='true')">
		<xsl:apply-templates select="gui:tab"/>
	</xsl:if>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Tabs({element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>'});
		<xsl:call-template name="gui:createobject"/>
	</script>
</div>
</xsl:template>

<xsl:template match="gui:tabs/gui:tab">
	<div class="" id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:choose>
				<xsl:when test="@background='light'"><xsl:text>in2igui_tabs_tab in2igui_tabs_tab_light</xsl:text></xsl:when>
				<xsl:otherwise><xsl:text>in2igui_tabs_tab</xsl:text></xsl:otherwise>
			</xsl:choose>
		</xsl:attribute>
		<xsl:attribute name="style">
			<xsl:if test="position()>1">display: none;</xsl:if>
			<xsl:if test="@padding">padding: <xsl:value-of select="@padding"/>px;</xsl:if>
		</xsl:attribute>
		<xsl:apply-templates/>
	</div>
</xsl:template>


<!-- Bound panel -->

<xsl:template match="gui:boundpanel">
	<div id="{generate-id()}" class="in2igui_boundpanel" style="display:none;">
		<div class="in2igui_boundpanel_arrow"><xsl:comment/></div>
		<div class="in2igui_boundpanel_top"><div><div><xsl:comment/></div></div></div>
		<div class="in2igui_boundpanel_body">
			<div class="in2igui_boundpanel_body">
				<div class="in2igui_boundpanel_body">
					<div class="in2igui_boundpanel_content">
						<xsl:attribute name="style">
							<xsl:if test="@width">width:<xsl:value-of select="@width"/>px;</xsl:if>
							<xsl:if test="@padding">padding:<xsl:value-of select="@padding"/>px;</xsl:if>
						</xsl:attribute>
						<xsl:apply-templates/>
						<xsl:comment/>
					</div>
				</div>
			</div>
		</div>
		<div class="in2igui_boundpanel_bottom"><div><div><xsl:comment/></div></div></div>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.BoundPanel({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
			<xsl:if test="@target">,target:'<xsl:value-of select="@target"/>'</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<!-- Window -->

<xsl:template match="gui:window">
	<div id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>in2igui_window</xsl:text>
			<xsl:if test="@variant"><xsl:text> in2igui_window_</xsl:text><xsl:value-of select="@variant"/></xsl:if>
		</xsl:attribute>
		<xsl:apply-templates select="gui:back"/>
		<div class="in2igui_window_front">
			<div class="in2igui_window_close"><xsl:comment/></div>
			<div class="in2igui_window_titlebar"><div><div>
				<xsl:if test="@icon">
					<span class="in2igui_window_icon" style="background-image: url('{$context}/In2iGui/icons/{@icon}1.png')"></span>
				</xsl:if>
				<span class="in2igui_window_title"><xsl:value-of select="@title"/></span>
			</div></div></div>
			<div class="in2igui_window_content"><div class="in2igui_window_content"><div class="in2igui_window_body">
	 			<xsl:attribute name="style"><xsl:if test="@width">width: <xsl:value-of select="@width"/>px;</xsl:if><xsl:if test="@padding">padding: <xsl:value-of select="@padding"/>px;</xsl:if></xsl:attribute>
				<xsl:apply-templates select="child::*[not(name()='back')]"/>
			</div></div></div>
			<div class="in2igui_window_bottom"><div class="in2igui_window_bottom"><div class="in2igui_window_bottom"><xsl:comment/></div></div></div>
		</div>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Window({element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>'});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:window/gui:back">
	<div class="in2igui_window_back">
		<xsl:apply-templates/>
		<xsl:comment/>
	</div>
</xsl:template>
<!-- Upload -->

<xsl:template match="gui:upload">
	<div class="in2igui_upload" id="{generate-id()}">
		<div class="in2igui_upload_items"><xsl:comment/></div>
		<div class="in2igui_upload_status"><xsl:comment/></div>
		<xsl:apply-templates/>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Upload({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>',
			url:'<xsl:value-of select="@url"/>',
			button:'<xsl:value-of select="@button"/>'
			<xsl:if test="@flash">,useFlash:<xsl:value-of select="@flash='true'"/></xsl:if>
			<xsl:if test="@chooseButton">,chooseButton:'<xsl:value-of select="@chooseButton"/>'</xsl:if>
			<xsl:if test="@widget">,widget:'<xsl:value-of select="@widget"/>'</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:upload/gui:placeholder">
	<div class="in2igui_upload_placeholder">
		<span class="in2igui_upload_icon"><xsl:comment/></span>
		<xsl:if test="@title"><h2><xsl:value-of select="@title"/></h2></xsl:if>
		<xsl:if test="@text"><p><xsl:value-of select="@text"/></p></xsl:if>
	</div>
</xsl:template>


<!-- Rich text -->

<xsl:template match="gui:richtext">
	<div class="in2igui_richtext" id="{generate-id()}">
		<div class="in2igui_richtext_toolbar" id="{generate-id()}_toolbar"><div class="in2igui_richtext_inner_toolbar"><div class="in2igui_richtext_toolbar_content" id="{generate-id()}_toolbar_content"><xsl:comment/></div></div></div>
		<iframe id="{generate-id()}_iframe" style="width: 100%; height: {@heigth}px;" frameborder="0"/>		
		<script type="text/javascript">
			var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.RichText({element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>'});
			<xsl:call-template name="gui:createobject"/>
		</script>
	</div>
</xsl:template>



<!-- Gallery -->

<xsl:template match="gui:gallery">
	<div class="in2igui_gallery" id="{generate-id()}">
		<xsl:if test="@padding"><xsl:attribute name="style">padding:<xsl:value-of select="@padding"/>px;</xsl:attribute></xsl:if>
		<xsl:comment/>
		<xsl:text>&#160;</xsl:text>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Gallery({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
			<xsl:if test="@source">,source:<xsl:value-of select="@source"/></xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>



<!-- Calendar -->

<xsl:template match="gui:calendar">
	<div class="in2igui_calendar" id="{generate-id()}">
		<xsl:if test="@state and @state!=//gui:gui/@state">
			<xsl:attribute name="style">display:none</xsl:attribute>
		</xsl:if>
	<div class="in2igui_calendar_bar">
		<xsl:comment/>
	</div>
	<table class="in2igui_calendar_weekview">
		<thead>
		<tr>
			<th class="day"><xsl:comment/></th>
			<th class="day"><xsl:comment/></th>
			<th class="day"><xsl:comment/></th>
			<th class="day"><xsl:comment/></th>
			<th class="day"><xsl:comment/></th>
			<th class="day"><xsl:comment/></th>
			<th class="day"><xsl:comment/></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td><div class="in2igui_calendar_day"><xsl:comment/></div></td>
			<td><div class="in2igui_calendar_day"><xsl:comment/></div></td>
			<td><div class="in2igui_calendar_day"><xsl:comment/></div></td>
			<td><div class="in2igui_calendar_day"><xsl:comment/></div></td>
			<td><div class="in2igui_calendar_day"><xsl:comment/></div></td>
			<td><div class="in2igui_calendar_day"><xsl:comment/></div></td>
			<td><div class="in2igui_calendar_day"><xsl:comment/></div></td>
		</tr>
		</tbody>
	</table>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Calendar({
			element:'<xsl:value-of select="generate-id()"/>'
			,name:'<xsl:value-of select="@name"/>'
			,state:'<xsl:value-of select="@state"/>'
			<xsl:if test="@startHour">,startHour:<xsl:value-of select="@startHour"/></xsl:if>
			<xsl:if test="@endHour">,endHour:<xsl:value-of select="@endHour"/></xsl:if>
			<xsl:if test="@source">,source:<xsl:value-of select="@source"/></xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>



<!-- Gallery -->

<xsl:template match="gui:graphviz">
	<div class="in2igui_graphviz" id="{generate-id()}">
		<div class="in2igui_graphviz_texts" style="position: relative;"><xsl:comment/></div>
		<canvas/>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Graphviz('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@name"/>');
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>


<!-- Chart -->

<xsl:template match="gui:chart">
	<div id="{generate-id()}"><div id="{generate-id()}_chart"><xsl:comment/></div></div>
	<script type="text/javascript">
		swfobject.embedSWF(
			"<xsl:value-of select="$context"/>/In2iGui/lib/openflashchart/open-flash-chart.swf",
			"<xsl:value-of select="generate-id()"/>_chart",
			"<xsl:value-of select="@width"/>",
			"<xsl:value-of select="@height"/>",
  			"9.0.0", "expressInstall.swf",
  			{"data-file":"<xsl:value-of select="@url"/>"}
  		);
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.FlashChart('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@name"/>');
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<!-- Picker -->

<xsl:template match="gui:picker">
	<div id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>in2igui_picker</xsl:text>
			<xsl:if test="@shadow='true'"><xsl:text> in2igui_picker_shadow</xsl:text></xsl:if>
		</xsl:attribute>
		<div class="in2igui_picker_top"><div><div></div></div></div>
		<div class="in2igui_picker_middle"><div class="in2igui_picker_middle">
			<xsl:if test="@title">
				<div class="in2igui_picker_title"><xsl:value-of select="@title"/></div>
			</xsl:if>
		<div class="in2igui_picker_container"><div class="in2igui_picker_content"><xsl:comment/></div></div>
		</div></div>
		<div class="in2igui_picker_bottom"><div><div></div></div></div>
	</div>
	<script type="text/javascript">
		(function() {
			var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Picker({
				element:'<xsl:value-of select="generate-id()"/>',
				name:'<xsl:value-of select="@name"/>'
				<xsl:if test="@item-height">,itemHeight:<xsl:value-of select="@item-height"/></xsl:if>
				<xsl:if test="@item-width">,itemWidth:<xsl:value-of select="@item-width"/></xsl:if>
			});
			<xsl:call-template name="gui:createobject"/>
			var items = [];
			<xsl:for-each select="gui:item">
				items.push({
					title:'<xsl:value-of select="@title"/>'
					,image:'<xsl:value-of select="@image"/>'
					,value:'<xsl:value-of select="@value"/>'
				});
			</xsl:for-each>
			<xsl:value-of select="generate-id()"/>_obj.setObjects(items);
		})();
	</script>
</xsl:template>

<xsl:template match="gui:articles">
	<div class="in2igui_articles" id="{generate-id()}"><xsl:comment/></div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Articles({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
			<xsl:if test="@source">,source:<xsl:value-of select="@source"/></xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="html:html">
	<xsl:copy-of select="child::*|child::text()"/>
</xsl:template>

<xsl:template match="gui:text">
	<div class="in2igui_text">
		<xsl:if test="@align">
			<xsl:attribute name="style">text-align:<xsl:value-of select="@align"/>;</xsl:attribute>
		</xsl:if>
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="gui:link">
	<a href="javascript:void(0);" class="in2igui_link" id="{generate-id()}"><span><xsl:apply-templates/></span></a>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Link({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:text/gui:header | gui:text/gui:h">
	<h1><xsl:apply-templates/></h1>
</xsl:template>

<xsl:template match="gui:text/gui:p">
	<p><xsl:apply-templates/></p>
</xsl:template>

<xsl:template match="gui:segmented" name="gui:segmented">
	<span class="in2igui_segmented" id="{generate-id()}">
		<xsl:for-each select="gui:item">
			<a href="javascript:void(0)" rel="{@value}">
				<xsl:if test="@value=../@value">
					<xsl:attribute name="class">in2igui_segmented_selected</xsl:attribute>
				</xsl:if>
				<xsl:if test="@icon">
					<span class="in2igui_icon_16" style="background-image: url('{$context}/In2iGui/icons/{@icon}16.png')"><xsl:comment/></span>
				</xsl:if>
				<xsl:if test="@title">
					<span class="in2igui_segmented_text"><xsl:value-of select="@title"/></span>
				</xsl:if>
			</a>
		</xsl:for-each>
	<xsl:comment/></span>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Segmented({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
			<xsl:if test="@value">,value:'<xsl:value-of select="@value"/>'</xsl:if>
			<xsl:if test="@allow-null='true'">,allowNull:true</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

</xsl:stylesheet>