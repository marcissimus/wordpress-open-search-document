<?php
echo '<?xml version="1.0" encoding="' . get_bloginfo( 'charset' ) . '"?>' . PHP_EOL;
?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/<?php do_action( 'osd_ns' ); ?>">
	<ShortName><?php bloginfo( 'name' ); ?></ShortName>
	<Description><?php bloginfo( 'description' ); ?></Description>
	<Url type="text/html" method="get" template="<?php echo site_url( '/?s={searchTerms}' ); ?>"></Url>
	<Url type="application/atom+xml" method="get" template="<?php echo add_query_arg( 's', '{searchTerms}', bloginfo( 'atom_url' ) ); ?>" />
	<Url type="application/rss+xml" method="get" template="<?php echo add_query_arg( 's', '{searchTerms}', bloginfo( 'rss2_url' ) ); ?>" />
	<Url type="application/x-suggestions+json" method="get" template="<?php echo rest_url( 'opensearch/1.1/suggestions?s={searchTerms}' ); ?>"/>
	<Contact><?php bloginfo( 'admin_email' ); ?></Contact>
	<LongName><?php bloginfo( 'name' ); ?> Web Search</LongName>
	<Tags>wordpress blog</Tags>
	<Query role="example" searchTerms="blog" />
	<Developer>johnnoone, Matthias Pfefferle</Developer>
	<Language><?php bloginfo( 'language' ); ?></Language>
	<OutputEncoding><?php bloginfo( 'charset' ); ?></OutputEncoding>
	<InputEncoding><?php bloginfo( 'charset' ); ?></InputEncoding>
	<?php do_action( 'osd_xml' ); ?>
</OpenSearchDescription>
