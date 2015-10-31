<?php
/**
 *    Copyright 2015 OhYea777
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Framework\Misc;

$GLOBALS = [];

/***************************[TIME CONSTS]***************************/
/**/                                                             /**/
/**/ define('ONE_SECOND',                                    1); /**/
/**/ define('ONE_MINUTE',                      ONE_SECOND * 60); /**/
/**/ define('ONE_HOUR',                        ONE_MINUTE * 60); /**/
/**/ define('ONE_DAY',                           ONE_HOUR * 24); /**/
/**/ define('ONE_WEEK',                            ONE_DAY * 7); /**/
/**/ define('ONE_MONTH',                          ONE_DAY * 30); /**/
/**/ define('SIX_MONTHS',                        ONE_MONTH * 6); /**/
/**/ define('ONE_YEAR',                          ONE_DAY * 365); /**/
/**/ define('DATE_FORMAT',                       'Y:m:d h:i:s'); /**/
/**/                                                             /**/
/*************************[END TIME CONSTS]*************************/


/****************************[SITE INFO]****************************/
/**/                                                             /**/
/**/ $GLOBALS['site']['name'] =                   'Ones Like Me'; /**/
/**/                                                             /**/
/**************************[END SITE INFO]**************************/


/***********************[DATABASE LOGIN INFO]***********************/
/**/                                                             /**/
/**/ $GLOBALS['mysql']['host'] =                    'localhost'; /**/
/**/ $GLOBALS['mysql']['port'] =                           3306; /**/
/**/ $GLOBALS['mysql']['name'] =                         'name'; /**/
/**/ $GLOBALS['mysql']['user'] =                         'user'; /**/
/**/ $GLOBALS['mysql']['pass'] =                     'password'; /**/
/**/ $GLOBALS['mysql']['prefix'] =                    'prefix_'; /**/
/**/                                                             /**/
/*********************[END DATABASE LOGIN INFO]*********************/


/***************************[COOKIE INFO]***************************/
/**/                                                             /**/
/**/ $GLOBALS['cookie']['name'] =                        'Token'; /**/
/**/ $GLOBALS['cookie']['timeout'] =                     ONE_DAY; /**/
/**/                                                             /**/
/*************************[END COOKIE INFO]*************************/


/**************************[SOFTWARE INFO]**************************/
/**/                                                             /**/
/**/ $GLOBALS['software']['version'] =         '0.0.1-PRE_ALPHA'; /**/
/**/ $GLOBALS['software']['simple'] =                    '0.0.1'; /**/
/**/                                                             /**/
/************************[END SOFTWARE INFO]************************/


/****************************[DEBUG INFO]***************************/
/**/                                                             /**/
/**/ $GLOBALS['debug']['on'] =                              true; /**/
/**/                                                             /**/
/****************************[END DEBUG]****************************/


/*******************[DO NOT EDIT BELOW THIS LINE]*******************/
class Config {

    /**
     * @return mixed
     */
    public static function get() {
        global $GLOBALS;

        if (func_num_args() == 0) return -1;

        $data = $GLOBALS;

        foreach (func_get_args() as $arg) {
            if (!isset($data[$arg])) return -1;

            $data = $data[$arg];
        }

        return $data;
    }

}

if (Config::get('debug', 'on')) {
    ini_set('display_errors', 'On');
    error_reporting(-1);
}