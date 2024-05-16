<?php

namespace DevOwl\RealCookieBanner\view\checklist;

// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * On lite version there should be a link to get pro version.
 * @internal
 */
class GetPro extends \DevOwl\RealCookieBanner\view\checklist\AbstractChecklistItem
{
    const IDENTIFIER = 'get-pro';
    // Documented in AbstractChecklistItem
    public function isChecked()
    {
        return $this->getFromOption(self::IDENTIFIER);
    }
    // Documented in AbstractChecklistItem
    public function toggle($state)
    {
        return $this->persistStateToOption(self::IDENTIFIER, $state);
    }
    // Documented in AbstractChecklistItem
    public function getTitle()
    {
        return \__('Buy a license of Real Cookie Banner PRO', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getDescription()
    {
        return \__('Get a PRO license at devowl.io to get more out of Real Cookie Banner!', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function isVisible()
    {
        return !$this->isPro();
    }
    // Documented in AbstractChecklistItem
    public function getLink()
    {
        if (\defined('RCB_PRO_VERSION')) {
            return \add_query_arg('feature', 'checklist', RCB_PRO_VERSION);
        } else {
            return \__('https://devowl.io/go/real-cookie-banner?source=rcb-lite', RCB_TD);
        }
    }
    // Documented in AbstractChecklistItem
    public function needsPro()
    {
        return \true;
    }
    // Documented in AbstractChecklistItem
    public function getLinkText()
    {
        return \__('Learn more', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getLinkTarget()
    {
        return '_blank';
    }
}
