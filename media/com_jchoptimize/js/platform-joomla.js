/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads
 *
 * @package   jchoptimize/joomla-platform
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2021 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

var jchPlatform = (function ($) {

    let jch_ajax_url_optimizeimages = 'index.php?option=com_jchoptimize&view=OptimizeImage&task=optimizeimage';
    let jch_ajax_url_multiselect = 'index.php?option=com_jchoptimize&view=Ajax&task=multiselect';
    let jch_ajax_url_smartcombine = 'index.php?option=com_jchoptimize&view=Ajax&task=smartcombine';

    /**
     *
     * @param int
     */
    var applyAutoSettings = function (int) {
        window.location.href = configure_url + "&task=applyAutoSettings&autosetting=s" + int;
    }

    /**
     *
     * @param setting
     */
    var toggleSetting = function (setting) {
        window.location.href = configure_url + "&task=toggleSetting&setting=" + setting;
    }

    var submitForm = function () {
        Joomla.submitbutton('config.save.component.apply');
    }

    return {
        jch_ajax_url_multiselect: jch_ajax_url_multiselect,
        jch_ajax_url_optimizeimages: jch_ajax_url_optimizeimages,
        jch_ajax_url_smartcombine: jch_ajax_url_smartcombine,
        applyAutoSettings: applyAutoSettings,
        toggleSetting: toggleSetting,
        submitForm: submitForm
    }

})(jQuery);