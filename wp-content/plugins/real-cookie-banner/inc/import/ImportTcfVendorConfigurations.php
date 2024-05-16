<?php

namespace DevOwl\RealCookieBanner\import;

use DevOwl\RealCookieBanner\lite\settings\TcfVendorConfiguration;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Trait to handle the importer for TCF Vendor configurations in the `Import` class.
 * @internal
 */
trait ImportTcfVendorConfigurations
{
    /**
     * Import TCF Vendor configurations from JSON.
     *
     * @param array $tcfVendorConfiguration
     */
    protected function doImportTcfVendorConfigurations($tcfVendorConfiguration)
    {
        if (!$this->isPro()) {
            return;
        }
        $currentVendors = \DevOwl\RealCookieBanner\import\Export::instance()->appendTcfVendorConfigurations()->finish()['tcfVendorConfigurations'];
        $tcfVendorConfigurationStatus = $this->getTcfVendorConfigurationStatus();
        foreach ($tcfVendorConfiguration as $index => $tcfVendorConfiguration) {
            if (!$this->handleCorruptTcfVendorConfiguration($tcfVendorConfiguration, $index)) {
                continue;
            }
            // Collect data
            $metas = $tcfVendorConfiguration['metas'];
            $post_name = $tcfVendorConfiguration['post_name'];
            $vendorId = \intval($metas['vendorId']);
            $post_status = $tcfVendorConfigurationStatus === 'keep' ? $tcfVendorConfiguration['post_status'] : $tcfVendorConfigurationStatus;
            // Save restrictive purposes as plain string in meta as WP 5.0 does not support that yet in REST API
            if (isset($metas[TcfVendorConfiguration::META_NAME_RESTRICTIVE_PURPOSES])) {
                $metas[TcfVendorConfiguration::META_NAME_RESTRICTIVE_PURPOSES] = \json_encode($metas[TcfVendorConfiguration::META_NAME_RESTRICTIVE_PURPOSES]);
            }
            // Find current blocker with same vendorId
            $found = \false;
            foreach ($currentVendors as $currentVendor) {
                if ($currentVendor['metas']['vendorId'] === $vendorId) {
                    $found = $currentVendor;
                    break;
                }
            }
            // A TCF Vendor configuration can only exist once for each vendor
            if ($found) {
                $this->addMessageSkipExistingTcfVendorConfiguration($vendorId);
                continue;
            }
            // Always create the entry
            $create = \wp_insert_post(['post_type' => TcfVendorConfiguration::CPT_NAME, 'post_status' => $post_status, 'meta_input' => $metas], \true);
            if (\is_wp_error($create)) {
                $this->addMessageCreateFailure($vendorId, \__('TCF Vendor', RCB_TD), $create);
                continue;
            } else {
                $this->mapTcfVendorConfigurations[$post_name] = \get_post($create)->ID;
            }
        }
    }
    /**
     * Check missing meta of passed configuration.
     *
     * @param array $tcfVendorConfiguration
     * @param int $index
     */
    protected function handleCorruptTcfVendorConfiguration($tcfVendorConfiguration, $index)
    {
        if (!isset($tcfVendorConfiguration['metas'], $tcfVendorConfiguration['post_status']) || !isset($tcfVendorConfiguration['metas']['vendorId'])) {
            $this->addMessageMissingProperties($index, \__('TCF Vendor configuration', RCB_TD), 'metas.[vendorId], post_status');
            return \false;
        }
        return \true;
    }
}
