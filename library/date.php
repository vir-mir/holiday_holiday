<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 20.11.13
 * Time: 12:36
 */

namespace library;


class Date {


    const DATE_MASK_RUSSIAN = "%d.%m.%Y",
        DATE_MASK_ENGLISH = "%m/%d/%Y",
        DATE_MASK_SQL = "%Y-%m-%d";


    private $_timestamp;


    public static $MONTHS_NAMES_CYRILLIC = array(
        "нет такого месяца",
        "январь",
        "февраль",
        "март",
        "апрель",
        "май",
        "июнь",
        "июль",
        "август",
        "сентябрь",
        "октябрь",
        "ноябрь",
        "декабрь"
    );


    public static $DAY_NAMES_ABBREVIATED = array(
        'Вс.',
        'Пн.',
        'Вт.',
        'Ср.',
        'Чт.',
        'Пт.',
        'Сб.'
    );



    /**
     * Конструктор класса Date, создает время в секундах изхоя от передонных параметров, если кроме $year не будет
     * переданно параметров то будет вызванна функция setFromString, а также можно оставить конструктор пустым тогда дата будет созданна на
     * текущее время.
     *
     * @param string $year
     * @param string $month
     * @param string $day
     */
    public function __construct($year = null, $month = null, $day = null) {
        $setFromString = (bool) (is_string($year) && ($month == null) && ($day == null));
        if ($setFromString) {
            $this->setFromString($year);
        } else {
            $info = getdate();
            if ($year==null)
                $year = $info["year"];
            if ($month==null)
                $month = $info["mon"];
            if ($day==null)
                $day = $info["mday"];
            $this->_timestamp = mktime(0, 0, 0, $month, $day, $year);
        }
    }


    /**
     * Проверям входит ли дата в переданный период
     *
     * @param Date $dateForComparisonStart
     * @param Date $dateForComparisonEnd
     * @return bollean
     */
    public function includePeriod(Date $dateForComparisonStart, Date $dateForComparisonEnd) {
        if(($this->lessThan($dateForComparisonEnd) || $this->equals($dateForComparisonEnd))
            &&
            ($this->greaterThan($dateForComparisonStart) || $this->equals($dateForComparisonStart))
        ) return true;
        else
            return false;
    }


    /**
     * Проверяет на меньше ли дата обьекта переданной
     *
     * @param Date $dateForComparison
     * @return bool
     */
    public function lessThan(Date $dateForComparison) {
        return $this->_timestamp < $dateForComparison->getTimestamp();
    }

    /**
     * Сравнивает дата
     *
     * @param Date $dateForComparison
     * @return bool
     */
    public function equals(Date $dateForComparison) {
        return $this->_timestamp == $dateForComparison->getTimestamp();
    }

    /**
     * Проверяет на больше ли дата обьекта переданной
     *
     * @param Date $dateForComparison
     * @return bool
     */
    public function greaterThan(Date $dateForComparison) {
        return $this->_timestamp > $dateForComparison->getTimestamp();
    }

    /**
     * Увеличивает или уменьшает день. По умолчанию передается 1.
     *
     * @param int $dayCount
     * @return Date
     */
    public function addDay($dayCount = 1) {
        $this->_timestamp = strtotime("+{$dayCount} day", $this->_timestamp);
        return $this;
    }

    /**
     * Увеличивает или уменьшает месяц. По умолчанию передается 1.
     *
     * @param int $monthCount
     * @return Date
     */
    public function addMonth($monthCount = 1) {
        $this->_timestamp = strtotime("+{$monthCount} month", $this->_timestamp);
        return $this;
    }

    /**
     * Возвращает количество дней в текущем месяце
     *
     * @return int
     */
    public function getDaysInCurrentMonth() {
        $year = $this->getYear();
        $month = $this->getMonth();
        return self::getDaysInMonthStatic($year, $month);
    }


    public static function getDaysInMonthStatic($year, $month) {
        $daysCount = array(1=>31, 3=>31, 4=>30, 5=>31, 6=>30, 7=>31, 8=>31, 9=>30, 10=>31, 11=>30, 12=>31);
        if ( $month == 2 )
            return $year%4==0?29:28;
        else
            return $daysCount[ $month ];
    }

    /**
     * Возвращает дату в переданном формате.
     *
     * @param string $formatString
     * @return string
     */
    public function toString($formatString) {
        return strftime($formatString, $this->_timestamp);
    }

    /**
     * Возвращает дату в SQL формате
     *
     * @return string
     */
    public function toStringSql() {
        return $this->toString(self::DATE_MASK_SQL);
    }

    /**
     * Возвращает дату в Русском формате
     *
     * @return string
     */
    public function toStringRus() {
        return $this->toString(self::DATE_MASK_RUSSIAN);
    }

    /**
     * Возвращает день.
     *
     * @return string
     */
    public function getDay() {
        $info = getdate($this->_timestamp);
        return $info["mday"];
    }

    /**
     * Возвращает день в году.
     *
     * @return string
     */
    public function getYearDay() {
        $info = getdate($this->_timestamp);
        return $info["yday"];
    }

    /**
     * Возвращает месяц.
     *
     * @return string
     */
    public function getMonth() {
        $info = getdate($this->_timestamp);
        return $info["mon"];
    }

    /**
     * Возвращает квартал.
     *
     * @return string
     */
    public function getQuarter() {
        return ceil($this->getMonth()/3);
    }

    /**
     * Возвращает год.
     *
     * @return string
     */
    public function getYear() {
        $info = getdate($this->_timestamp);
        return $info["year"];
    }

    /**
     * Возвращает время в секундах от создания люнекса.
     *
     * @return int
     */
    public function getTimestamp() {
        return $this->_timestamp;
    }

    /**
     * Затает время в секундах от создания люнекса, изходя из переданной даты разделители ("/", ".", "-")
     *
     * @param string $dateString
     * @return Date
     */
    public function setFromString($dateString) {
        @list($first, $second, $third) =  preg_split('~[/.-]+~i', $dateString);
        $splitterPos = max(strpos($dateString, "/"), max(strpos($dateString, "."), strpos($dateString, "-")));
        $splitter = substr($dateString, $splitterPos, 1);
        switch ($splitter) {
            case "/":
                $ts = mktime(0, 0, 0, $first, $second, $third);
                break;
            case ".":
                $ts = mktime(0, 0, 0, $second, $first, $third);
                break;
            case "-":
                $ts = mktime(0, 0, 0, $second, $third, $first);
                break;
        }
        @$this->_timestamp = $ts;

        return $this;
    }

    /**
     * Устанавлевает первый день месяц
     * @return Date
     */
    public function setToFirstDayOfMonth() {
        $this->_timestamp = mktime(0, 0, 0, $this->getMonth(), 1, $this->getYear());
        return $this;
    }

    /**
     * Устанавлевает последний день месяц
     * @return Date
     */
    public function setToLastDayOfMonth() {
        $this->_timestamp = mktime(0, 0, 0, $this->getMonth(), $this->getDaysInCurrentMonth(), $this->getYear());
        return $this;
    }

    /**
     * Устанавлевает первый день года
     * @return Date
     */
    public function setToFirstDayOfYear() {
        $this->_timestamp = mktime(0, 0, 0, 1, 1, $this->getYear());
        return $this;
    }

    /**
     * Устанавлевает последний день года
     * @return Date
     */
    public function setToLastDayOfYear() {
        $this->_timestamp = mktime(0, 0, 0, 12, 31, $this->getYear());
        return $this;
    }

    /**
     * Устанавлевает первый день квартала
     * @return Date
     */
    public function setToFirstDayOfQuarter() {
        $this->_timestamp = mktime(0, 0, 0, floor(($this->getMonth()-1)/3)*3+1, 1, $this->getYear());
        return $this;
    }

    /**
     * Устанавливает дату на понедельник
     * @return Date
     */
    public function setToMonday()
    {
        while(!$this->isMonday())$this->addDay(-1);
        return $this;
    }

    /**
     * Устанавливает дату на воскресенье
     * @return Date
     */
    public function setToSunday()
    {
        $this->setToMonday();
        $this->addDay(6);
        return $this;
    }

    /**
     * Проверка даты на понедельник
     *
     * @return bool
     */
    public function isMonday()
    {
        $dateInfo = getdate($this->_timestamp);
        return ($dateInfo["wday"]==1);
    }

    /**
     * Возвращает день недели
     *
     * @return int
     */
    public function getDayOfWeek()
    {
        $dateInfo = getdate($this->_timestamp);
        if($dateInfo["wday"] == 0) $dateInfo["wday"] = 7;
        return $dateInfo["wday"];
    }

    /**
     *
     * @param Date $date
     */
    public function getWeek() {


        $date = clone $this;

        $date->setToFirstDayOfYear();
        $weekDay = date('w', $date->getTimestamp());
        //если первый день года >четверга, то по исо эта неделя нулевая. Нужно чтобы была первая
        if($weekDay == 5 || $weekDay == 6 || $weekDay == 0) {
            $weekOffset = 1;
        } else {
            $weekOffset = 0;
        }

        $date = clone $this;

        $weeksNum = date('W', $date->getTimestamp()) + $weekOffset;



        return $weeksNum;

    }



    /**
     * Принимает дату ву формате SQL, и возвращает массив недель месяца, содержащий Date с датой начала и датой конца недели.
     *
     * @param string $dateSql
     * @return array
     */
    public static function getWeeksOfMonth($dateSql) {

        if($dateSql instanceof Date) {
            $date = clone $dateSql;
        } else {
            $date = new Date($dateSql);
        }
        $date->setToFirstDayOfYear();
        $weekDay = date('w', $date->getTimestamp());
        //если первый день года >четверга, то по исо эта неделя нулевая. Нужно чтобы была первая
        if($weekDay == 5 || $weekDay == 6 || $weekDay == 0) {
            $weekOffset = 1;
        } else {
            $weekOffset = 0;
        }


        if($dateSql instanceof Date) {
            $date = clone $dateSql;
        } else {
            $date = new Date($dateSql);
        }


        $date->setToFirstDayOfMonth();
        $month = $date->getMonth();


        $weeks = array();

        //первая неделя, если первый день месяца пнд-четверг - учитываем
        if($date->getMonth()==1) {//Первая неделя месяца может быть 52, поэтому для января вручную еденицу
            $weeksNum = 1;
        } else {
            $weeksNum = date('W', $date->getTimestamp()) + $weekOffset;
        }

        $weekDay = date('w', $date->getTimestamp());
        if($weekDay == 1 || $weekDay == 2 || $weekDay == 3 || $weekDay == 4) {

            $date->setToMonday();
            $weeks[$weeksNum]['begin'] = clone $date;
            $date->addDay(6);
            $weeks[$weeksNum]['end'] = clone $date;
        }
        //вторая неделя, вск
        $weeksNum++;
        $date->setToMonday();
        $date->addDay(7 + 6);

        while($date->getMonth() == $month) {

            $weeks[$weeksNum]['end'] = clone $date;
            $date->addDay(-6);
            $weeks[$weeksNum]['begin'] = clone $date;

            $date->addDay(6+7);
            $weeksNum++;
        }

        //сейчас мы на следующем вск, после последнего вск месяца
        $date->setToFirstDayOfMonth();
        $weekDay = date('w', $date->getTimestamp());
        //если первый день след месяца> четверга, значит неделю на стыке записываем
        if($weekDay == 5 || $weekDay == 6 || $weekDay == 0) {

            $date->setToMonday();
            $weeks[$weeksNum]['begin'] = clone $date;
            $date->addDay(6);
            $weeks[$weeksNum]['end'] = clone $date;
        }

        return $weeks;
    }

}

