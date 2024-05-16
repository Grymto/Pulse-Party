<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker;

/**
 * Common constants for the headless content blocker.
 * @internal
 */
abstract class Constants
{
    /**
     * When transforming a blocked attribute, it gets prefixed and suffixed.
     */
    const HTML_ATTRIBUTE_CAPTURE_PREFIX = 'consent-original';
    const HTML_ATTRIBUTE_CAPTURE_CLICK_PREFIX = 'consent-click-original';
    const HTML_ATTRIBUTE_CAPTURE_SUFFIX = '_';
    // Some plugins are using something like replace(/src=/) (like WP Rocket)
    /**
     * The ID of the `Blockable` for which we need consent for.
     */
    const HTML_ATTRIBUTE_BLOCKER_ID = 'consent-id';
    /**
     * Usually, this is `cookie`, but you could also implement different types like
     * `tcf-vendor` or similar. So, in frontend you can implement different "checking
     * for consent"-mechanism.
     */
    const HTML_ATTRIBUTE_BY = 'consent-by';
    /**
     * Comma separated list of IDs needed for `consent-by`.
     */
    const HTML_ATTRIBUTE_COOKIE_IDS = 'consent-required';
    /**
     * A tag got blocked, e. g. `iframe`. We can now determine the "Visual Parent". The visual parent is the
     * closest parent where the content blocker should be placed to. The visual parent can be configured as follow:
     *
     * - `false` = Use original element
     * - `true` = Use parent element
     * - `number` = Go upwards x element (parentElement)
     * - `string` = Go upwards until parentElement matches a selector
     * - `string` = Starting with `children:` you can `querySelector` down to create the visual parent for a children
     */
    const HTML_ATTRIBUTE_VISUAL_PARENT = 'consent-visual-use-parent';
    /**
     * This attribute is only used on client-side and allows to automatically open the hero dialog as soon as
     * the visual content blocker is rendered. This is great to use in conjunction with `window.consentApi.unblock`.
     */
    const HTML_ATTRIBUTE_HERO_DIALOG_DEFAULT_OPEN = 'consent-hero-dialog-default-open';
    /**
     * Use this together with `HTML_ATTRIBUTE_VISUAL_PARENT`. When the parent got found, it gets automatically hidden.
     */
    const HTML_ATTRIBUTE_VISUAL_PARENT_HIDE = 'consent-visual-use-parent-hide';
    /**
     * When an individual node has the attribute it will behavior as the same setting known from the
     * content blocker setting "Force visual content blocker for hidden elements".
     */
    const HTML_ATTRIBUTE_VISUAL_FORCE = 'consent-visual-force';
    /**
     * See `CalculateUniqueKeys`.
     */
    const HTML_ATTRIBUTE_VISUAL_ID = 'consent-visual-id';
    /**
     * This constant is only used in frontend and is added afterwards a unblock transaction
     * got completed.
     */
    const HTML_ATTRIBUTE_UNBLOCKED_TRANSACTION_COMPLETE = 'consent-transaction-complete';
    /**
     * Caching plugins compatibility e.g. WP Rocket. Adds this `type` to your
     * `script` and `style` so it gets not combined to a combine-file for example.
     */
    const HTML_ATTRIBUTE_TYPE_FOR = ['script', 'style'];
    const HTML_ATTRIBUTE_TYPE_NAME = 'type';
    const HTML_ATTRIBUTE_TYPE_VALUE = 'application/consent';
    const HTML_ATTRIBUTE_TYPE_JS = 'application/javascript';
    const HTML_ATTRIBUTE_TYPE_CSS = 'text/css';
    /**
     * Blocked styles are put into an own attribute instead of the `script` body
     * to avoid that caching plugins like WP Rocket will bundle them in a minified file.
     */
    const HTML_ATTRIBUTE_INLINE_STYLE = 'consent-inline-style';
    const URL_QUERY_ARG_ORIGINAL_URL_IN_STYLE = 'consent-original-url';
    const HTML_ATTRIBUTE_INLINE = 'consent-inline';
    // For scripts
    /**
     * HTML tags with this attribute activated are ignored in the complete content blocker.
     */
    const HTML_ATTRIBUTE_CONSENT_SKIP_BLOCKER = 'consent-skip-blocker';
    const HTML_ATTRIBUTE_CONSENT_SKIP_BLOCKER_VALUE = '1';
    const HTML_POTENTIAL_SKIP_TAGS = ['script', 'link', 'style'];
    /**
     * See `ImagePreview`.
     */
    const HTML_ATTRIBUTE_THUMBNAIL = 'consent-thumbnail';
    const HTML_ATTRIBUTE_THUMBNAIL_SUGGESTION = 'consent-thumbnail-suggestion';
    /**
     * See `ReattachDom`
     */
    const HTML_ATTRIBUTE_REATTACH_DOM = 'consent-redom';
    /**
     * See `DelegateClick`.
     */
    const HTML_ATTRIBUTE_DELEGATE_CLICK = 'consent-delegate-click';
    /**
     * See `JQueryHijackEach`.
     */
    const HTML_ATTRIBUTE_JQUERY_HIJACK_EACH = 'consent-jquery-hijack-each';
    /**
     * If set it will trigger a window resize event when the unblocked item got clicked (or delegated a click).
     * This needs to be defined as number as it can be delayed. Use `0` for instant dispatch.
     */
    const HTML_ATTRIBUTE_CLICK_DISPATCH_RESIZE = 'consent-click-dispatch-resize';
    /**
     * See `Confirm`
     */
    const HTML_ATTRIBUTE_CONFIRM = 'consent-confirm';
    /**
     * See `NegatePlugin`
     */
    const HTML_ATTRIBUTE_NEGATE = 'consent-negate';
    /**
     * Allow to skip an inline script to be blocked when it contains a given string. This is only necessary for found
     * matches which do not have another indicator in the HTML tag.
     */
    const INLINE_SCRIPT_CONTAINING_STRING_TO_SKIP_BLOCKER = 'CONTENT_BLOCKER_SKIP_THIS_INLINE_SCRIPT';
    const INLINE_SCRIPT_CONTAINING_STRING_TO_SKIP_BLOCKER_UNMINIFYABLE = 'if(window.CONTENT_BLOCKER_SKIP_THIS_INLINE_SCRIPT){};';
}
