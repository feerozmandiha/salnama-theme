<?php
/**
 * Title: index
 * Slug: salnama-theme/index
 * Inserter: no
 */
?>
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button"></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">در باره ما</a></div>
<!-- /wp:button -->

<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">ارتباط با ما</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group"><!-- wp:buttons {"metadata":{"categories":["salmama-cta"],"patternName":"salmama-cta/header-contact","name":"CTA هدر - تماس و مشاوره"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button"></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:query {"queryId":0,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"layout":{"contentSize":null,"type":"constrained"}} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"default"}} -->
<!-- wp:group -->
<div class="wp-block-group"><!-- wp:post-title {"isLink":true} /-->

<!-- wp:post-featured-image {"isLink":true} /-->

<!-- wp:post-content /-->

<!-- wp:group {"style":{"typography":{"fontSize":"14px"}},"layout":{"type":"flex"}} -->
<div class="wp-block-group" style="font-size:14px"><!-- wp:post-author {"showAvatar":false,"showBio":false} /-->

<!-- wp:post-date {"datetime":"2025-10-25T12:03:27.641Z","isLink":true,"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}}} /-->

<!-- wp:post-terms {"term":"category"} /-->

<!-- wp:post-terms {"term":"post_tag"} /--></div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"40px"} -->
<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:group {"layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:query-pagination -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination --></div>
<!-- /wp:group --></div>
<!-- /wp:query --></main>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button"></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/ثبت-سفارش-1024x468.png" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/Brown-and-Cream-Minimalist-Business-Proposal-Presentation-1024x576.jpg" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->