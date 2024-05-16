<?php

namespace DevOwl\RealCookieBanner\comp\migration;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\settings\TCF;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Migration for Major version 2.
 *
 * @see https://app.clickup.com/t/g75t1p
 * @internal
 */
class DashboardTileTcfV2IllegalUsage extends \DevOwl\RealCookieBanner\comp\migration\AbstractDashboardTileMigration
{
    const EXPECTED_VERSION = '2.14.2';
    // Documented in AbstractDashboardTileMigration
    public function actions()
    {
        $this->addAction('paragraph1', '', \sprintf('<p style="margin-top:0;">%s</p>', \join('</p><p>', [\__('Briefly summarized, consents according to TCF are considered too non-transparent, not understandable for the website visitor and therefore invalid. IAB Europe, publisher of TCF, is required to redesign the standard within six months so that it collects legally effective consents. At the same time, IAB Europe is taking legal action against the decision of the data protection authority.', RCB_TD), \__('Real Cookie Banner is obliged to implement mandatory requirements of the IAB Europe. <strong>We point out that with the current version of Real Cookie Banner it is not possible to obtain lawful consents with the version of TCF integrated in it.</strong> Therefore, we strongly recommend that you do not use the TCF integration until the IAB Europe has made the necessary changes (this will be implemented in Real Cookie Banner via an update).', RCB_TD), \__('If you have previously obtained consents under the Transparency & Consent Framework, be sure to read the following additional information and consult a legal advisor to clarify the further procedure for your individual case.', RCB_TD), \sprintf('<ul>
    <li><a href="%s" target="_blank">%s</a></li>
    <li><a href="%s" target="_blank">%s</a></li>
    <li><a href="%s" target="_blank">%s</a></li>
</ul>', \esc_attr(\__('https://devowl.io/go/real-cookie-banner/tcf/2020-02/apd-decision', RCB_TD)), \__('Decision of the Belgian Data Protection Authority (APD)', RCB_TD), \esc_attr(\__('https://devowl.io/go/real-cookie-banner/tcf/2020-02/iab-europe-statement', RCB_TD)), \__('Statement of the IAB Europe (English)', RCB_TD), \esc_attr(\__('https://devowl.io/go/real-cookie-banner/tcf/2020-02/evaluation-ra-schwenke', RCB_TD)), \__('Legal assessment by RA Dr. Schwenke (German)', RCB_TD))])), ['linkText' => \__('Disable TCF', RCB_TD), 'callback' => $this->getConfigUrl('/settings/tcf')]);
    }
    // Documented in AbstractDashboardTileMigration
    public function getId()
    {
        return 'tcfV2';
    }
    // Documented in AbstractDashboardTileMigration
    public function getHeadline()
    {
        return \__('Consents obtained via TCF illegal', RCB_TD);
    }
    // Documented in AbstractDashboardTileMigration
    public function getDescription()
    {
        return \__('We need to inform you that on Feb. 02, 2022, the Belgian Data Protection Authority (APD) declared the collection of consents under the Transparency & Consent Framework (TCF) to be invalid. The decision is effective across the EU as it was issued in a one-stop-shop procedure.', RCB_TD);
    }
    // Documented in AbstractDashboardTileMigration
    public function isActive()
    {
        $isTcfActive = TCF::getInstance()->isActive();
        $previousVersions = Core::getInstance()->getActivator()->getPreviousDatabaseVersions();
        $found = \false;
        foreach ($previousVersions as $v) {
            if (\version_compare($v, self::EXPECTED_VERSION, '<=')) {
                $found = \true;
                break;
            }
        }
        if ($found && !$isTcfActive) {
            $this->dismiss();
            return \false;
        }
        return $found;
    }
    // Documented in AbstractDashboardTileMigration
    public function dismiss()
    {
        return Core::getInstance()->getActivator()->removePreviousPersistedVersions(function ($v) {
            return \version_compare($v, self::EXPECTED_VERSION, '>');
        });
    }
}
