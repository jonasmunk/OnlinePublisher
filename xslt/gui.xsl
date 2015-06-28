<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
	xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:gui="uri:hui"
	xmlns:html="http://www.w3.org/1999/xhtml"
    version="1.0"
    exclude-result-prefixes="gui"
    >

<!--xsl:include href="iphone.xsl"/-->
<xsl:include href="layout.xsl"/>
<xsl:include href="formula.xsl"/>
<xsl:include href="toolbar.xsl"/>
<xsl:include href="links.xsl"/>
<xsl:include href="other.xsl"/>

<xsl:output encoding="UTF-8" omit-xml-declaration="yes" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>

<!--doc title:'Gui' module:'base'
<gui title="«text»" state="«text»" padding="«pixels»">
    <controller source="«url»"/>
    <controller source="«url»"/>
    ···
</gui>
-->
<xsl:template match="gui:gui">
	<html class="hui">

		<head>
			<xsl:if test="$profile='true'">
				<script>
					console.profile();
					window.setTimeout(function() {console.profileEnd()},5000);
				</script>
			</xsl:if>
			<title><xsl:value-of select="@title"/></title>
			<meta http-equiv="X-UA-Compatible" content="IE=edge" />
			<xsl:choose>
				<xsl:when test="$dev='true'">
					<link rel="stylesheet" href="{$context}/hui/{$pathVersion}bin/development.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
				</xsl:when>
				<xsl:otherwise>
					<link rel="stylesheet" href="{$context}/hui/{$pathVersion}bin/minimized.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
				</xsl:otherwise>
			</xsl:choose>
	
			<xsl:comment><![CDATA[[if IE 8]>
				<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$context"/><![CDATA[/hui/]]><xsl:value-of select="$pathVersion"/><![CDATA[css/msie8.css]]><![CDATA["> </link>
			<![endif]]]></xsl:comment>
			<xsl:comment><![CDATA[[if lt IE 7]>
				<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$context"/><![CDATA[/hui/]]><xsl:value-of select="$pathVersion"/><![CDATA[css/msie6.css]]><![CDATA["> </link>
			<![endif]]]></xsl:comment>
			<xsl:comment><![CDATA[[if IE 7]>
				<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$context"/><![CDATA[/hui/]]><xsl:value-of select="$pathVersion"/><![CDATA[css/msie7.css]]><![CDATA["> </link>
			<![endif]]]></xsl:comment>
	
			<xsl:if test="//gui:graph">
				<link rel="stylesheet" href="{$context}/hui/{$pathVersion}css/graph.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
			</xsl:if>
			<xsl:if test="//gui:diagram">
				<link rel="stylesheet" href="{$context}/hui/{$pathVersion}css/diagram.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
			</xsl:if>
			<xsl:if test="//gui:keyboard-navigator">
				<link rel="stylesheet" href="{$context}/hui/{$pathVersion}css/keyboardnavigator.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
			</xsl:if>
			<xsl:if test="//gui:chart">
				<link rel="stylesheet" href="{$context}/hui/{$pathVersion}css/chart.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
			</xsl:if>
			<xsl:if test="//gui:tiles">
				<link rel="stylesheet" href="{$context}/hui/{$pathVersion}css/tiles.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
			</xsl:if>
			<xsl:if test="//gui:timeline">
				<link rel="stylesheet" href="{$context}/hui/{$pathVersion}css/timeline.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
			</xsl:if>
			<xsl:if test="//gui:skeleton">
				<link rel="stylesheet" href="{$context}/hui/{$pathVersion}css/skeleton.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
			</xsl:if>
	
			<xsl:for-each select="//gui:css">
				<link rel="stylesheet" href="{@url}" type="text/css" media="screen" title="no title" charset="utf-8"/>
			</xsl:for-each>
			<xsl:for-each select="//gui:style[@source]">
				<link rel="stylesheet" href="{@source}" type="text/css" media="screen" title="no title" charset="utf-8"/>
			</xsl:for-each>
			<xsl:for-each select="//gui:style[not[@source]]">
				<style>
					<xsl:value-of select="."/>
				</style>
			</xsl:for-each>
			
			<xsl:comment><![CDATA[[if lt IE 9]>
				<script type="text/javascript" src="]]><xsl:value-of select="$context"/><![CDATA[/hui/]]><xsl:value-of select="$pathVersion"/><![CDATA[bin/compatibility.min.js]]><![CDATA["></script>
			<![endif]]]></xsl:comment>
            
			
			<xsl:choose>
				<xsl:when test="$dev='true'">
					<script type="text/javascript">
						_context = '<xsl:value-of select="$context"/>/hui';
					</script>
					<script src="{$context}/hui/bin/development.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
				</xsl:when>
				<xsl:otherwise>
					<script src="{$context}/hui/{$pathVersion}bin/minimized.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
				</xsl:otherwise>
			</xsl:choose>

			<xsl:for-each select="//gui:require">
				<script src="{$context}/hui/{$pathVersion}{@path}" type="text/javascript" charset="utf-8"><xsl:comment/></script>	
			</xsl:for-each>
	
			<xsl:if test="//gui:graph">
				<script src="{$context}/hui/{$pathVersion}js/Graph.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			</xsl:if>
			<xsl:if test="//gui:chart">
				<script src="{$context}/hui/{$pathVersion}js/Chart.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			</xsl:if>
			<xsl:if test="//gui:timeline">
				<script src="{$context}/hui/{$pathVersion}js/TimeLine.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			</xsl:if>
			<xsl:if test="//gui:columns">
				<script src="{$context}/hui/{$pathVersion}js/Columns.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			</xsl:if>
			<xsl:if test="//gui:keyboard-navigator">
				<script src="{$context}/hui/{$pathVersion}js/KeyboardNavigator.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			</xsl:if>
			<xsl:if test="//gui:diagram">
				<script src="{$context}/hui/{$pathVersion}js/Drawing.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>	
				<script src="{$context}/hui/{$pathVersion}js/Diagram.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			</xsl:if>
			<xsl:if test="//gui:tiles">
				<script src="{$context}/hui/{$pathVersion}js/Tiles.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			</xsl:if>
			<xsl:if test="//gui:skeleton">
				<script src="{$context}/hui/{$pathVersion}js/Skeleton.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			</xsl:if>
			<xsl:if test="//gui:pages">
				<link rel="stylesheet" href="{$context}/hui/{$pathVersion}css/pages.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
				<script src="{$context}/hui/{$pathVersion}js/Pages.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			</xsl:if>
			<xsl:if test="//gui:object-input">
				<link rel="stylesheet" href="{$context}/hui/{$pathVersion}css/objectinput.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
				<script src="{$context}/hui/{$pathVersion}js/ObjectInput.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			</xsl:if>
	
			<xsl:for-each select="gui:localize[@source]">
				<script src="{@source}" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			</xsl:for-each>
			<xsl:for-each select="gui:controller[@source]">
				<script src="{@source}" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			</xsl:for-each>
	
			<script type="text/javascript">
				hui.ui.context = '<xsl:value-of select="$context"/>';
				<xsl:if test="@state">
					hui.ui.state = '<xsl:value-of select="@state"/>';
				</xsl:if>
				<xsl:if test="$language">
					hui.ui.language = '<xsl:value-of select="$language"/>';
				</xsl:if>
				<xsl:for-each select="gui:controller[@source]">
					<xsl:if test="@name">
					if (window['<xsl:value-of select="@name"/>']!==undefined) {
						hui.ui.listen(<xsl:value-of select="@name"/>);
					}
					</xsl:if>
				</xsl:for-each>
			</script>
	
			<xsl:call-template name="dwr-setup"/>
		</head>
		<body>
			<xsl:attribute name="class">
				<xsl:text>hui</xsl:text>
				<xsl:if test="@background">
					<xsl:text> hui_bg_</xsl:text><xsl:value-of select="@background"/>
				</xsl:if>
			</xsl:attribute>
			<xsl:if test="gui:dock">
				<xsl:attribute name="style">overflow:hidden;</xsl:attribute>
			</xsl:if>
			<xsl:choose>
				<xsl:when test="@padding">
					<div style="padding: {@padding}px;" class="hui_body"><xsl:apply-templates/></div>
				</xsl:when>
				<xsl:otherwise>
					<xsl:apply-templates/>
				</xsl:otherwise>
			</xsl:choose>
		</body>
	</html>
</xsl:template>

<xsl:template match="gui:style"></xsl:template>

<!--doc title:'DWR interface' module:'base'
<dwr base="«url»">
    <interface name="«text»"/>
    <interface name="«text»"/>
</dwr>
-->
<xsl:template name="dwr-setup">
	<xsl:if test="gui:dwr">
		<script src="{$context}{gui:dwr/@base}engine.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		<xsl:for-each select="gui:dwr/gui:interface">
			<script src="{$context}{../@base}interface/{@name}.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		</xsl:for-each>
		<script type="text/javascript">
			dwr.engine.setErrorHandler(hui.ui.dwrErrorHandler);
		</script>
	</xsl:if>
</xsl:template>




<xsl:template name="gui:createobject">
	<xsl:if test="@name and @name!='' and not(//gui:subgui[@globals='false'])">
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




<!--doc title:'Data source' class:'hui.ui.Source' module:'base'
<source url="«url»" dwr="«text»" lazy="«boolean»">
    <parameter key="«text»" value="«expression»"/>
</source>
-->
<xsl:template match="gui:source">
	<script type="text/javascript">
		(function() {
			var parameters = [];
			<xsl:for-each select="gui:parameter">
				parameters.push({key:'<xsl:value-of select="@key"/>',value:'<xsl:value-of select="@value"/>'})
			</xsl:for-each>
			
		
			var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Source({
				name : '<xsl:value-of select="@name"/>',
				parameters : parameters
				<xsl:choose>
					<xsl:when test="@url">,url:'<xsl:value-of select="@url"/>'</xsl:when>
					<xsl:when test="@dwr">,dwr:'<xsl:value-of select="@dwr"/>'</xsl:when>
				</xsl:choose>
				<xsl:if test="@lazy='true'">,lazy:true</xsl:if>
			});
			<xsl:call-template name="gui:createobject"/>
		})()
	</script>
</xsl:template>




<!--doc title:'Sub-GUI' module:'base'
<subgui>
    ···
</subgui>
-->
<xsl:template name="gui:subgui">
	<div>
		<xsl:apply-templates/>
	</div>
</xsl:template>




<!--doc title:'Script' module:'base'
<script>
    «text»
</script>
-->
<xsl:template match="gui:script">
	<script type="text/javascript">
		<xsl:apply-templates/>
	</script>
</xsl:template>




<!--doc title:'Listener' module:'base'
<listen>
    <«name» for="«event»">
        «text»
    </«name»>
</listen>
-->
<xsl:template match="gui:listen">
	<script type="text/javascript">
		<xsl:choose>
			<xsl:when test="@for">
				(function() {
					var listener = {};
					<xsl:for-each select="*">
						listener['$<xsl:value-of select="local-name()"/>$<xsl:value-of select="../@for"/>']=function() {<xsl:apply-templates/>};
					</xsl:for-each>
					hui.ui.listen(listener);
				})()		
			</xsl:when>
			<xsl:otherwise>
				hui.ui.listen({
					<xsl:value-of select="."/>
				});
			</xsl:otherwise>
		</xsl:choose>
	</script>	
</xsl:template>





<!--doc title:'Dock' class:'hui.ui.Dock' module:'layout'
<dock url="«url»" frame-name="«text»" position="« top | bottom »" name="«name»">
    <sidebar>
        ···
    </sidebar>
    <tabs/> | <toolbar/>
</dock>
-->
<xsl:template match="gui:dock">
	<xsl:call-template name="gui:dock-internals"/>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Dock({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
			<xsl:if test="gui:tabs">,tabs:true</xsl:if>
			<xsl:if test="gui:sidebar/@collapsed='true'">,collapsed:true</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:dock[gui:sidebar]">
	<div class="hui_dock" id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>hui_dock</xsl:text>
			<xsl:if test="gui:sidebar/@collapsed='true'"> hui_dock_sidebar_collapsed</xsl:if>
		</xsl:attribute>
		<xsl:apply-templates select="gui:sidebar"/>
		<div class="hui_dock_sidebar_main">
			<xsl:call-template name="gui:dock-internals"/>
		</div>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Dock({element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>'<xsl:if test="gui:tabs">,tabs:true</xsl:if>});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template name="gui:dock-internals">
	<div class="">
		<xsl:attribute name="class">
			<xsl:text>hui_dock_internals</xsl:text>
			<xsl:choose>
				<xsl:when test="@position='top' or not(@position)">
					<xsl:text> hui_dock_internals_top</xsl:text>
					<xsl:if test="gui:tabs">
						<xsl:text> hui_dock_internals_top_tabs</xsl:text>
					</xsl:if>
				</xsl:when>
				<xsl:when test="@position='bottom'">
					<xsl:text> hui_dock_internals_bottom</xsl:text>
					<xsl:if test="gui:tabs">
						<xsl:text> hui_dock_internals_bottom_tabs</xsl:text>
					</xsl:if>
				</xsl:when>
			</xsl:choose>
		</xsl:attribute>
		<xsl:if test="not(gui:sidebar)">
			<xsl:attribute name="id"><xsl:value-of select="generate-id()"/></xsl:attribute>
		</xsl:if>
		<div class="hui_dock_bar">
			<xsl:apply-templates select="child::*[not(name()='sidebar')]"/>
		</div>
		<div class="hui_dock_body">			
			<div class="hui_dock_progress"><xsl:comment/></div>
			<iframe src="{@url}" frameborder="0" name="{@frame-name}"><xsl:comment/></iframe>
		</div>
	</div>
</xsl:template>

<xsl:template name="gui:dock-internalsx">
	<table class="hui_dock">
		<xsl:if test="not(gui:sidebar)">
			<xsl:attribute name="id"><xsl:value-of select="generate-id()"/></xsl:attribute>
		</xsl:if>
		<xsl:attribute name="class">
			<xsl:text>hui_dock</xsl:text>
			<xsl:if test="gui:sidebar">
				<xsl:text> hui_dock_with_sidebar</xsl:text>
			</xsl:if>
		</xsl:attribute>
		<xsl:if test="@position='top' or not(@position)">
			<thead>
				<tr><td class="hui_dock_bar">
					<xsl:if test="gui:toolbar">
						<xsl:attribute name="style">height: 57px;</xsl:attribute>
					</xsl:if>
					<xsl:apply-templates/>
				</td></tr>
			</thead>
		</xsl:if>
		<xsl:if test="@position='bottom'">
			<tfoot>
				<xsl:if test="gui:tabs"><xsl:attribute name="class">hui_dock_tabs</xsl:attribute></xsl:if>
				<tr><td class="hui_dock_bar">
					<xsl:apply-templates select="child::*[not(name()='sidebar')]"/>
				</td></tr>
			</tfoot>
		</xsl:if>
		<tbody>
			<tr><td>
				<div class="hui_dock_progress"><xsl:comment/></div>
				<iframe src="{@url}" frameborder="0" name="{@frame-name}"/>
			</td></tr>
		</tbody>
	</table>
</xsl:template>

<xsl:template match="gui:dock/gui:sidebar">
	<div class="hui_dock_sidebar hui_context_sidebar">
		<xsl:apply-templates/>
		<xsl:comment/>
	</div>
	<div class="hui_dock_sidebar_line"><xsl:comment/></div>
</xsl:template>




<!--doc title:'Frames' module:'layout'
<frames>
	<frame name="«text»" source="«url»" scrolling="«boolean»"/>
</frames>
-->
<xsl:template match="gui:frames">
	<html>
		<head>
			<script src="{$context}/hui/{$pathVersion}bin/minimized.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			<xsl:apply-templates select="gui:script"/>
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





<!--doc title:'IFrame' class:'hui.ui.IFrame' module:'layout'
<iframe source="«url»" id="«text»" state="«text»" name="«name»" height="«pixels»" />
-->
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
		<xsl:if test="@state and (not(//gui:gui/@state) or @state!=//gui:gui/@state)">
			<xsl:text>display:none;</xsl:text>
		</xsl:if>
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
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.IFrame({
			element:'<xsl:value-of select="$id"/>',
			name:'<xsl:value-of select="@name"/>'
			<xsl:if test="@state">
				,state:'<xsl:value-of select="@state"/>'
			</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>
	


<!--doc title:'Selection' class:'hui.ui.Selection' module:'selection'
<selection name="«name»" value="«text»" state="«text»" value="«text»">
    <item value="«text»" title="«text»" badge="«text»" icon="«icon»" />
    <items ··· />
    <title>«text»</title>
</selection>
-->
<xsl:template match="gui:selection">
	<div class="hui_selection" id="{generate-id()}">
		<xsl:if test="@top">
			<xsl:attribute name="style">margin-top: <xsl:value-of select="@top"/>px;</xsl:attribute>
		</xsl:if>
		<xsl:if test="@state and (not(//gui:gui/@state) or @state!=//gui:gui/@state)">
			<xsl:attribute name="style">display:none</xsl:attribute>
		</xsl:if>
		<xsl:apply-templates/>
	</div>
	<script type="text/javascript">
		!function() {
			var items = [];
			<xsl:for-each select="gui:item">
			items.push({
				id : '<xsl:value-of select="generate-id()"/>',
				title : '<xsl:value-of select="@title"/>',
				icon : '<xsl:value-of select="@icon"/>',
				badge : '<xsl:value-of select="@badge"/>',
				value : '<xsl:value-of select="@value"/>',
				kind : '<xsl:value-of select="@kind"/>'
			});
			</xsl:for-each>
		
		
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Selection({
			element : '<xsl:value-of select="generate-id()"/>',
			name : '<xsl:value-of select="@name"/>',
			state : '<xsl:value-of select="@state"/>',
			items : items
			<xsl:if test="@value">,value:'<xsl:value-of select="@value"/>'</xsl:if>
		});
		with (<xsl:value-of select="generate-id()"/>_obj) {
			<xsl:for-each select="gui:items">
				registerItems(<xsl:value-of select="generate-id()"/>_obj);
			</xsl:for-each>
		}
		<xsl:call-template name="gui:createobject"/>
		}();
	</script>
</xsl:template>

<xsl:template match="gui:selection/gui:item">
	<div id="{generate-id()}">
		<xsl:attribute name="class">hui_selection_item<xsl:if test="@value=../@value"> hui_selected</xsl:if></xsl:attribute>
		<xsl:if test="@badge"><strong class="hui_selection_badge"><xsl:value-of select="@badge"/></strong></xsl:if>
		<xsl:if test="@icon">
			<span>
				<xsl:attribute name="style">background-image: url('<xsl:value-of select="$context"/>/hui/icons/<xsl:value-of select="@icon"/>16.png');</xsl:attribute>
				<xsl:attribute name="class">hui_icon_1</xsl:attribute>
				<xsl:comment/>
			</span>
		</xsl:if>
		<span class="hui_selection_label">
		<xsl:value-of select="@title"/><xsl:value-of select="@text"/>
		</span>
	</div>
</xsl:template>



<!--doc title:'Selection items' class:'hui.ui.Selection.Items' module:'selection'
<selection ···>
    <items name="«text»" title="«text»" source="«source»" title="«text»" />
</selection>
-->
<xsl:template match="gui:selection/gui:items">
	<xsl:if test="@title">
		<div class="hui_selection_title" id="{generate-id()}_title" style="display: none;"><span><xsl:value-of select="@title"/></span></div>
	</xsl:if>
	<div id="{generate-id()}">
		<xsl:comment/>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Selection.Items({
			element:'<xsl:value-of select="generate-id()"/>'
			,name:'<xsl:value-of select="@name"/>'
			<xsl:if test="@source">,source:<xsl:value-of select="@source"/></xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:selection/gui:title">
	<div class="hui_selection_title"><span><xsl:value-of select="."/></span></div>
</xsl:template>





<!--doc title:'List' class:'hui.ui.List' module:'selection'
<list name="«text»" state="«text»" url="«url»" source="«source»" selectable="«boolean»">
    <window size="«integer»" />
    <column key="«text»" title="«text»" width="«'min'»" />
</list>
-->
<xsl:template match="gui:list">
	<div id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>hui_list</xsl:text>
			<xsl:if test="@variant">
				<xsl:text> hui_list_</xsl:text><xsl:value-of select="@variant"/>
			</xsl:if>
		</xsl:attribute>
		<xsl:if test="@state and (not(//gui:gui/@state) or @state!=//gui:gui/@state) or @visible='false'">
			<xsl:attribute name="style">display:none</xsl:attribute>
		</xsl:if>
		<div class="hui_list_progress"><xsl:comment/></div>
		<div class="hui_list_navigation">
			<div class="hui_list_selection window_page"><div><div class="window_page_body"><xsl:comment/></div></div></div>
			<span class="hui_list_count"><xsl:comment/></span>
		</div>
		<div class="hui_list_body">
			<table cellspacing="0" cellpadding="0">
				<thead>
					<tr>
					<xsl:apply-templates select="gui:column"/>
					</tr>
				</thead>
				<tbody>
					<xsl:if test="not(@selectable) or @selectable='true'">
						<xsl:attribute name="class">hui_list_selectable</xsl:attribute>
					</xsl:if>
					<xsl:comment/>
				</tbody>
			</table>
			<xsl:if test="gui:empty">
				<div class="hui_list_empty">
					<xsl:apply-templates select="gui:empty"/>
				</div>
			</xsl:if>
			<xsl:if test="gui:error">
				<div class="hui_list_error_content">
					<xsl:if test="gui:error/@text">
						<p class="hui_list_error_text"><xsl:value-of select="gui:error/@text"/></p>
					</xsl:if>
					<xsl:apply-templates select="gui:error"/>
				</div>
			</xsl:if>
		</div>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.List({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>',
			url:'<xsl:value-of select="@url"/>',
			<xsl:if test="@source">source:<xsl:value-of select="@source"/>,</xsl:if>
			<xsl:if test="@selectable">selectable:<xsl:value-of select="@selectable"/>,</xsl:if>
			state:'<xsl:value-of select="@state"/>',
			windowSize:'<xsl:value-of select="gui:window/@size"/>'
			<xsl:if test="@drop-files='true'">,dropFiles:true</xsl:if>
			<xsl:if test="@indent">,indent:<xsl:value-of select="@indent"/></xsl:if>
			<xsl:if test="@remember='true'">,rememberSelection:true</xsl:if>
		});
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
		<xsl:if test="@width='min'">
			<xsl:attribute name="style">width: 1%;</xsl:attribute>
		</xsl:if>
		<xsl:value-of select="@title"/>
	</th>
</xsl:template>






<!--doc title:'Tabs' class:'hui.ui.Tabs' module:'layout'
<tabs name="«text»" small="«boolean»" centered="«boolean»" below="«boolean»">
    <tab title="«text»" padding="«pixels»">
        ···
    </tab>
    <tab title="«text»" padding="«pixels»">
        ···
    </tab>
</tabs>
-->
<xsl:template match="gui:tabs">
<div id="{generate-id()}" class="hui_tabs">
	<xsl:if test="@below='true'">
		<xsl:apply-templates select="gui:tab"/>
	</xsl:if>
	<div>
		<xsl:attribute name="class">
			<xsl:text>hui_tabs_bar</xsl:text>
			<xsl:choose>
				<xsl:when test="@small='true' and @below='true'">
					<xsl:text> hui_tabs_bar_small_below</xsl:text>
				</xsl:when>
				<xsl:when test="@small='true'">
					<xsl:text> hui_tabs_bar_small</xsl:text>
				</xsl:when>
			</xsl:choose>
			<xsl:if test="@centered='true'">
				<xsl:text> hui_tabs_bar_centered</xsl:text>
			</xsl:if>
		</xsl:attribute>
		<ul>
		<xsl:for-each select="gui:tab">
			<li id="{generate-id()}_tab">
				<xsl:if test="position()=1">
					<xsl:attribute name="class">hui_tabs_selected</xsl:attribute>
				</xsl:if>
				<a><span><span><xsl:value-of select="@title"/></span></span></a>
			</li>
		</xsl:for-each>
		</ul>
	</div>
	<xsl:if test="not(@below='true')">
		<xsl:apply-templates select="gui:tab"/>
	</xsl:if>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Tabs({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</div>
</xsl:template>

<xsl:template match="gui:tabs/gui:tab">
	<div class="" id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:choose>
				<xsl:when test="@background='light'"><xsl:text>hui_tabs_tab hui_tabs_tab_light</xsl:text></xsl:when>
				<xsl:otherwise><xsl:text>hui_tabs_tab</xsl:text></xsl:otherwise>
			</xsl:choose>
		</xsl:attribute>
		<xsl:attribute name="style">
			<xsl:if test="position()>1">display: none;</xsl:if>
			<xsl:if test="@padding">padding: <xsl:value-of select="@padding"/>px;</xsl:if>
		</xsl:attribute>
		<xsl:apply-templates/>
	</div>
</xsl:template>






<!--doc title:'Bound panel' class:'hui.ui.BoundPanel' module:'container'
<boundpanel name="«name»" target="«name»" width="«pixels»" padding="«pixels»">
    ···
</boundpanel>
-->
<xsl:template match="gui:boundpanel">
	<div id="{generate-id()}" style="display:none;">
		<xsl:attribute name="class">
			<xsl:text>hui_boundpanel</xsl:text>
			<xsl:if test="@variant">
				<xsl:text> hui_boundpanel_</xsl:text><xsl:value-of select="@variant"/>
			</xsl:if>
		</xsl:attribute>
		<div class="hui_boundpanel_arrow"><xsl:comment/></div>
		<div class="hui_boundpanel_top"><div><div><xsl:comment/></div></div></div>
		<div class="hui_boundpanel_body">
			<div class="hui_boundpanel_body">
				<div class="hui_boundpanel_body">
					<div class="hui_boundpanel_content">
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
		<div class="hui_boundpanel_bottom"><div><div><xsl:comment/></div></div></div>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.BoundPanel({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
			<xsl:if test="@target">,target:'<xsl:value-of select="@target"/>'</xsl:if>
			<xsl:if test="@variant">,variant:'<xsl:value-of select="@variant"/>'</xsl:if>
			<xsl:if test="@modal='true'">,modal:true</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>





<!--doc title:'Window' class:'hui.ui.Window' module:'container'
<window name="«name»" title="«text»" icon="«icon»" close="«boolean»" width="«pixels»" padding="«pixels»" variant="« dark | light | news »">
    ···
    <back>
        ···
    </back>
</window>
-->
<xsl:template match="gui:window">
	<div id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>hui_window</xsl:text>
			<xsl:if test="@variant">
				<xsl:text> hui_window_</xsl:text><xsl:value-of select="@variant"/>
			</xsl:if>
			<xsl:if test="@variant='dark'">
				<xsl:text> hui_context_dark</xsl:text>
			</xsl:if>
		</xsl:attribute>
		<xsl:apply-templates select="gui:back"/>
		<div class="hui_window_front">
			<xsl:if test="not(@close='false')">
				<div class="hui_window_close"><xsl:comment/></div>
			</xsl:if>
			<div class="hui_window_titlebar"><div><div>
				<xsl:if test="@icon">
					<span class="hui_window_icon" style="background-image: url('{$context}/hui/icons/{@icon}16.png')"></span>
				</xsl:if>
				<span class="hui_window_title"><xsl:value-of select="@title"/></span>
			</div></div></div>
			<div class="hui_window_content"><div class="hui_window_content"><div class="hui_window_body">
	 			<xsl:attribute name="style"><xsl:if test="@width">width: <xsl:value-of select="@width"/>px;</xsl:if><xsl:if test="@padding">padding: <xsl:value-of select="@padding"/>px;</xsl:if></xsl:attribute>
				<xsl:apply-templates select="child::*[not(name()='back')]"/>
			</div></div></div>
			<div class="hui_window_bottom"><div class="hui_window_bottom"><div class="hui_window_bottom"><xsl:comment/></div></div></div>
		</div>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Window({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:window/gui:back">
	<div class="hui_window_back">
		<xsl:apply-templates/>
		<xsl:comment/>
	</div>
</xsl:template>





<!--doc title:'Upload' class:'hui.ui.Upload' module:'unknown'
<upload name="«name»" url="«url»" button="«text»" chooseButton="«name»" widget="«name»">
    <placeholder title="«text»" text="«text»"/>
</upload>
-->
<xsl:template match="gui:upload">
	<div class="hui_upload" id="{generate-id()}">
		<div class="hui_upload_items"><xsl:comment/></div>
		<div class="hui_upload_status"><xsl:comment/></div>
		<xsl:apply-templates/>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Upload({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>',
			url:'<xsl:value-of select="@url"/>',
			button:'<xsl:value-of select="@button"/>'
			<xsl:if test="@multiple">,multiple:<xsl:value-of select="@multiple='true'"/></xsl:if>
			<xsl:if test="@chooseButton">,chooseButton:'<xsl:value-of select="@chooseButton"/>'</xsl:if>
			<xsl:if test="@widget">,widget:'<xsl:value-of select="@widget"/>'</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:upload/gui:placeholder">
	<div class="hui_upload_placeholder">
		<span class="hui_upload_icon"><xsl:comment/></span>
		<xsl:if test="@title"><h2><xsl:value-of select="@title"/></h2></xsl:if>
		<xsl:if test="@text"><p><xsl:value-of select="@text"/></p></xsl:if>
	</div>
</xsl:template>




<!--
doc title:'Rich text' class:'hui.ui.RichText'
<richtext name="«name»" height="«pixels»" />

<xsl:template match="gui:richtext">
	<div class="hui_richtext" id="{generate-id()}">
		<div class="hui_richtext_toolbar" id="{generate-id()}_toolbar"><div class="hui_richtext_inner_toolbar"><div class="hui_richtext_toolbar_content" id="{generate-id()}_toolbar_content"><xsl:comment/></div></div></div>
		<iframe id="{generate-id()}_iframe" style="width: 100%; height: {@heigth}px;" frameborder="0"/>		
		<script type="text/javascript">
			var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.RichText({
				element:'<xsl:value-of select="generate-id()"/>',
				name:'<xsl:value-of select="@name"/>'
			});
			<xsl:call-template name="gui:createobject"/>
		</script>
	</div>
</xsl:template>
-->




<!--doc title:'Gallery' class:'hui.ui.Gallery' module:'selection'
<gallery name="«name»" source="«name»" padding="«pixels»" state="«state»" drop-files="«boolean»"/>
-->
<xsl:template match="gui:gallery">
	<div class="hui_gallery" id="{generate-id()}">
		<xsl:if test="@state and (not(//gui:gui/@state) or @state!=//gui:gui/@state)">
			<xsl:attribute name="style">display:none</xsl:attribute>
		</xsl:if>
		<div class="hui_gallery_progress"><xsl:comment/></div>
		<div class="hui_gallery_body">
			<xsl:if test="@padding"><xsl:attribute name="style">padding:<xsl:value-of select="@padding"/>px;</xsl:attribute></xsl:if>
			<xsl:comment/>
			<xsl:text>&#160;</xsl:text>
		</div>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Gallery({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
			<xsl:if test="@source">,source:<xsl:value-of select="@source"/></xsl:if>
			<xsl:if test="@state">,state:'<xsl:value-of select="@state"/>'</xsl:if>
			<xsl:if test="@drop-files='true'">,dropFiles:true</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>






<!--doc title:'Calendar' class:'hui.ui.Calendar' module:'selection'
<calendar name="«name»" source="«name»" start-hour="«integer»" end-hour="«integer»" />
-->
<xsl:template match="gui:calendar">
	<div class="hui_calendar" id="{generate-id()}">
		<xsl:if test="@state and (not(//gui:gui/@state) or @state!=//gui:gui/@state)">
			<xsl:attribute name="style">display:none</xsl:attribute>
		</xsl:if>
	<div class="hui_calendar_bar">
		<xsl:comment/>
	</div>
	<table class="hui_calendar_weekview">
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
			<td><div class="hui_calendar_day"><xsl:comment/></div></td>
			<td><div class="hui_calendar_day"><xsl:comment/></div></td>
			<td><div class="hui_calendar_day"><xsl:comment/></div></td>
			<td><div class="hui_calendar_day"><xsl:comment/></div></td>
			<td><div class="hui_calendar_day"><xsl:comment/></div></td>
			<td><div class="hui_calendar_day"><xsl:comment/></div></td>
			<td><div class="hui_calendar_day"><xsl:comment/></div></td>
		</tr>
		</tbody>
	</table>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Calendar({
			element:'<xsl:value-of select="generate-id()"/>'
			,name:'<xsl:value-of select="@name"/>'
			,state:'<xsl:value-of select="@state"/>'
			<xsl:if test="@start-hour">,startHour:<xsl:value-of select="@start-hour"/></xsl:if>
			<xsl:if test="@end-hour">,endHour:<xsl:value-of select="@end-hour"/></xsl:if>
			<xsl:if test="@source">,source:<xsl:value-of select="@source"/></xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>





<!--doc title:'Graphviz' class:'hui.ui.Graphviz' module:'selection'
<graphviz name="«name»" />
-->
<xsl:template match="gui:graphviz">
	<div class="hui_graphviz" id="{generate-id()}">
		<div class="hui_graphviz_texts" style="position: relative;"><xsl:comment/></div>
		<canvas/>
	</div>
	<script type="text/javascript">
		hui.require('<xsl:value-of select="$context"/>/hui/ext/Graphviz.js',function() {
			var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Graphviz('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@name"/>');
			<xsl:call-template name="gui:createobject"/>
		});
	</script>
</xsl:template>




<!--doc title:'Picker' class:'hui.ui.Picker' module:'selection'
<picker name="«name»" shadow="«boolean»" title="«text»" item-height="«pixels»" item-width="«pixels»">
    <item title="«text»" value="«text»" image="«url»" />
    <item title="«text»" value="«text»" image="«url»" />
</picker>
-->
<xsl:template match="gui:picker">
	<div id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>hui_picker</xsl:text>
			<xsl:if test="@shadow='true'"><xsl:text> hui_picker_shadow</xsl:text></xsl:if>
		</xsl:attribute>
			<xsl:if test="@title">
				<div class="hui_picker_title"><xsl:value-of select="@title"/></div>
			</xsl:if>
		<div class="hui_picker_container"><div class="hui_picker_content"><xsl:comment/></div></div>
		<div class="hui_picker_pages"><a>1</a><a>2</a><a>3</a></div>
	</div>
	<script type="text/javascript">
		(function() {
			var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Picker({
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





<!--doc title:'HTML' module:'layout'
<html xmlns="«'http://www.w3.org/1999/xhtml'»">
    ···
</html>
-->
<xsl:template match="html:html">
	<xsl:copy-of select="child::*|child::text()"/>
</xsl:template>

<xsl:template match="gui:html">
	<xsl:copy-of select="child::*|child::text()"/>
</xsl:template>

<xsl:template match="gui:div|gui:span|gui:strong|gui:p|gui:em|gui:a|gui:input">
	<xsl:element name="{name()}">
		<xsl:if test="@style">
			<xsl:attribute name="style"><xsl:value-of select="@style"/></xsl:attribute>
		</xsl:if>
		<xsl:if test="@class">
			<xsl:attribute name="class"><xsl:value-of select="@class"/></xsl:attribute>
		</xsl:if>
		<xsl:if test="@id">
			<xsl:attribute name="id"><xsl:value-of select="@id"/></xsl:attribute>
		</xsl:if>
		<xsl:if test="@href">
			<xsl:attribute name="href"><xsl:value-of select="@href"/></xsl:attribute>
		</xsl:if>
		<xsl:if test="@onclick">
			<xsl:attribute name="onclick"><xsl:value-of select="@onclick"/></xsl:attribute>
		</xsl:if>
		<xsl:apply-templates/>
	</xsl:element>
</xsl:template>







<!--doc title:'Text' module:'layout'
<text align="«'left' | 'center' | 'right'»" top="«pixels»" bottom="«pixels»">
    <h|header>···</h|header>
    <p>···</p>
    ···
</text>
-->
<xsl:template match="gui:text">
	<div class="hui_text">
		<xsl:attribute name="style">
			<xsl:if test="@align">text-align:<xsl:value-of select="@align"/>;</xsl:if>
			<xsl:if test="@top">padding-top:<xsl:value-of select="@top"/>px;</xsl:if>
			<xsl:if test="@bottom">padding-bottom:<xsl:value-of select="@bottom"/>px;</xsl:if>
		</xsl:attribute>
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="gui:text/gui:header | gui:text/gui:h">
	<h1><xsl:apply-templates/></h1>
</xsl:template>

<xsl:template match="gui:text/gui:p">
	<p><xsl:apply-templates/></p>
</xsl:template>




<!--doc title:'Link' class:'hui.ui.Link' module:'action'
<link name="«name»">
    ····
</link>
-->
<xsl:template match="gui:link">
	<a href="javascript://" class="hui_link" id="{generate-id()}"><span><xsl:apply-templates/></span></a>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Link({
			element : '<xsl:value-of select="generate-id()"/>',
			name : '<xsl:value-of select="@name"/>'
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>




	<!--doc title:'Diagram' class:'hui.ui.Diagram' module:'visalization'
	<diagram name="«name»"/>
	-->
	<xsl:template match="gui:diagram">
		<div class="hui_diagram" id="{generate-id()}">
			<xsl:attribute name="style">
				<xsl:choose>
				<xsl:when test="not(@height) or @height='full'">
					<xsl:text>height:100%;</xsl:text>
				</xsl:when>
				<xsl:when test="@height">
					<xsl:text>height:</xsl:text><xsl:value-of select="@height"/><xsl:text>px;</xsl:text>
				</xsl:when>
				</xsl:choose>
				<xsl:if test="@width">
					<xsl:text>width:</xsl:text><xsl:value-of select="@width"/><xsl:text>px;</xsl:text>
				</xsl:if>
			</xsl:attribute>
			<xsl:comment/>
		</div>
		<script type="text/javascript">
			var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Diagram({
				element : '<xsl:value-of select="generate-id()"/>',
				name : '<xsl:value-of select="@name"/>'
				<xsl:if test="@source">
					,source:<xsl:value-of select="@source"/>
				</xsl:if>
			});
			<xsl:call-template name="gui:createobject"/>
		</script>
	</xsl:template>



	<!--doc title:'Chart' class:'hui.ui.Chart' module:'visalization'
	<chart name="«name»" source="«name»"/>
	-->
	<xsl:template match="gui:chart">
		<div class="hui_chart" id="{generate-id()}">
			<xsl:attribute name="style">
				<xsl:if test="@width">
					<xsl:text>width:</xsl:text><xsl:value-of select="@width"/><xsl:text>px;</xsl:text>
				</xsl:if>
				<xsl:choose>
					<xsl:when test="substring(@height, string-length(@height))='%'">
						<xsl:text>height:</xsl:text><xsl:value-of select="@height"/><xsl:text>;</xsl:text>
					</xsl:when>
					<xsl:when test="@height">
						<xsl:text>height:</xsl:text><xsl:value-of select="@height"/><xsl:text>px;</xsl:text>
					</xsl:when>
				</xsl:choose>
			</xsl:attribute>
			<xsl:comment/></div>
		<script type="text/javascript">
			var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Chart({
				element : '<xsl:value-of select="generate-id()"/>',
				name : '<xsl:value-of select="@name"/>'
				<xsl:if test="@source">
					,source:<xsl:value-of select="@source"/>
				</xsl:if>
			});
			<xsl:call-template name="gui:createobject"/>
		</script>
	</xsl:template>



	<!--doc title:'Segmented' class:'hui.ui.Segmented' module:'input'
	<segmented name="«name»" value="«text»" allow-null="«boolean»">
	    <item text="«text»" value="«text»" icon="«icon»" />
	</segmented>
	-->
	<xsl:template match="gui:segmented" name="gui:segmented">
		<span id="{generate-id()}">
			<xsl:if test="@top">
				<xsl:attribute name="style">
					<xsl:text>margin-top: </xsl:text><xsl:value-of select="@top"/><xsl:text>px;</xsl:text>
				</xsl:attribute>
			</xsl:if>
			<xsl:attribute name="class">
				<xsl:text>hui_segmented</xsl:text>
				<xsl:if test="@variant">
					<xsl:text> hui_segmented_</xsl:text><xsl:value-of select="@variant"/>
				</xsl:if>
				<xsl:if test="not(@variant)">
					<xsl:text> hui_segmented_standard</xsl:text>
				</xsl:if>
			</xsl:attribute>
			<xsl:for-each select="gui:item">
				<a href="javascript:void(0)" rel="{@value}">
					<xsl:if test="@value=../@value">
						<xsl:attribute name="class">hui_segmented_selected</xsl:attribute>
					</xsl:if>
					<xsl:if test="@icon">
						<span class="hui_icon_16" style="background-image: url('{$context}/hui/icons/{@icon}16.png')"><xsl:comment/></span>
					</xsl:if>
					<xsl:if test="@title or @text">
						<span class="hui_segmented_text"><xsl:value-of select="@title"/><xsl:value-of select="@text"/></span>
					</xsl:if>
				</a>
			</xsl:for-each>
		<xsl:comment/></span>
		<script type="text/javascript">
			var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Segmented({
				element:'<xsl:value-of select="generate-id()"/>',
				name:'<xsl:value-of select="@name"/>'
				<xsl:if test="@key">,key:'<xsl:value-of select="@key"/>'</xsl:if>
				<xsl:if test="@value">,value:'<xsl:value-of select="@value"/>'</xsl:if>
				<xsl:if test="@allow-null='true'">,allowNull:true</xsl:if>
			});
			<xsl:call-template name="gui:createobject"/>
		</script>
	</xsl:template>





	<!--doc title:'Rendering' class:'hui.ui.Rendering' module:'layout'
	<rendering name="«name»"/>
	-->
	<xsl:template match="gui:rendering">
		<div class="hui_rendering" id="{generate-id()}">
			<xsl:apply-templates/>
		</div>
		<script type="text/javascript">
			var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Rendering({
				element : '<xsl:value-of select="generate-id()"/>',
				name : '<xsl:value-of select="@name"/>'
			});
			<xsl:call-template name="gui:createobject"/>
		</script>

	</xsl:template>

	<!--doc title:'Menu' class:'hui.ui.Menu' module:'layout'
	<menu name="«name»">
	    <item text="«text»" value="«text»"/>
	    <divider>
	    <item text="«text»" value="«text»">
	    	<item text="«text»" value="«text»"/>
		</item>
	</menu>
	-->
	<xsl:template match="gui:menu">
		<script type="text/javascript">
			(function() {
				var menu = <xsl:value-of select="generate-id()"/>_obj = hui.ui.Menu.create({name:'<xsl:value-of select="@name"/>'});
				var items = [];
				<xsl:apply-templates/>
				menu.addItems(items);
				<xsl:call-template name="gui:createobject"/>
			})();
		</script>
	
	</xsl:template>

	<xsl:template match="gui:menu//gui:item">
		items.push({text:'<xsl:value-of select="@text"/>',value:'<xsl:value-of select="@value"/>',children:(function() {
			var items = [];
			<xsl:apply-templates/>
			return items;
		})()});
	</xsl:template>

	<xsl:template match="gui:menu//gui:divider">
		items.push(null);
	</xsl:template>

	<xsl:template match="gui:keyboard-navigator">
		<script type="text/javascript">
			var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.KeyboardNavigator({
				element : '<xsl:value-of select="generate-id()"/>',
				name : '<xsl:value-of select="@name"/>'
			});
			<xsl:call-template name="gui:createobject"/>
		</script>
	</xsl:template>

    <xsl:template match="gui:finder">
        <script type="text/javascript">
            (function() {
    			var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Finder({
    				name : '<xsl:value-of select="@name"/>',
                    url : '<xsl:value-of select="@url"/>',
    				title : '<xsl:value-of select="@title"/>',
    				list : {url:'<xsl:value-of select="@list-url"/>'},
    				selection : {
    					url : '<xsl:value-of select="@selection-url"/>',
    					value : '<xsl:value-of select="@selection-value"/>',
    					parameter : '<xsl:value-of select="@selection-parameter"/>'
    				},
    				search : {parameter : '<xsl:value-of select="@search-parameter"/>'}
    			});
    			<xsl:call-template name="gui:createobject"/>
            })();
        </script>
    </xsl:template>
</xsl:stylesheet>