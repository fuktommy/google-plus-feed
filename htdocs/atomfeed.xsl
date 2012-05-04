<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns="http://www.w3.org/1999/xhtml"
>
<xsl:output method="html" encoding="utf-8" />
<xsl:template match="/">
  <html xml:lang="ja">
  <head>
    <title><xsl:value-of select="//atom:feed/atom:title"/></title>
    <link rel="stylesheet" href="/style.css" type="text/css" />
  </head>
  <body>
  <h1><a href="{//atom:feed/atom:link[@rel='alternate']/@href}">
      <xsl:value-of select="//atom:feed/atom:title"/></a></h1>
  <p><xsl:value-of select="//atom:feed/atom:subtitle"/></p>
  <dl>
    <xsl:apply-templates select="//atom:feed/atom:entry"/>
  </dl>
  </body>
  </html>
</xsl:template>

<xsl:template match="//atom:feed/atom:entry">
  <dt><a href="{./atom:link[@rel='alternate']/@href}">
      <xsl:value-of select="./atom:title"/></a></dt>
  <dd><xsl:value-of select="atom:published"/></dd>
  <dd><xsl:value-of select="atom:summary"/></dd>
</xsl:template>

</xsl:stylesheet>
