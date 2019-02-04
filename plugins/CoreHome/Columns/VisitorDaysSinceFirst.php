<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\CoreHome\Columns;

use Piwik\Plugin\Dimension\VisitDimension;
use Piwik\Tracker\Action;
use Piwik\Tracker\Request;
use Piwik\Tracker\Visitor;

// TODO: it would be better to save the time duration since the first visit rather than just the number of days, since that cuts off information.
// or would the timestamp of the first visit be even better? make matomo 4 issue?
class VisitorDaysSinceFirst extends VisitDimension
{
    protected $columnName = 'visitor_days_since_first';
    protected $columnType = 'SMALLINT(5) UNSIGNED NULL';
    protected $segmentName = 'daysSinceFirstVisit';
    protected $nameSingular = 'General_DaysSinceFirstVisit';
    protected $type = self::TYPE_NUMBER;

    /**
     * @param Request $request
     * @param Visitor $visitor
     * @param Action|null $action
     * @return mixed
     */
    public function onNewVisit(Request $request, Visitor $visitor, $action)
    {
        // if the visitor is known, force days since first to 0, to ignore any potential bad values for _idts
        if (!$visitor->isVisitorKnown()) {
            return 0;
        }

        return $request->getDaysSinceFirstVisit();
    }

    /**
     * @param Request $request
     * @param Visitor $visitor
     * @param Action|null $action
     * @return mixed
     */
    public function onAnyGoalConversion(Request $request, Visitor $visitor, $action)
    {
        return $visitor->getVisitorColumn($this->columnName);
    }
}