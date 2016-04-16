<?
  //NAMESPACE
  namespace SB\Functions;

  //FUNCTION convertSQLDialect
  function convertSQLDialect($sql, $dialectsource, $dialectdestination) {
    switch (strtolower(trim($dialectsource))) {
      case "mysql"  :
        switch (strtolower(trim($dialectdestination))) {
          case "postgresql"  :
            $sql = convertSQLDialect_MySQL2ProgreSQL($sql);
            break;
        }
        break;
    }

    return $sql;
  }


  //FUNCTION convertSQLDialect_MySQL2ProgreSQL
  function convertSQLDialect_MySQL2ProgreSQL($sql) {
    //add extra spatie
    $sql .= " ";

    //verwijder mysql directives
    $sql = preg_replace('/SQL_NO_CACHE/i', '', $sql);
    $sql = preg_replace('/SQL_CALC_FOUND_ROWS/i', '', $sql);

    //verwijder force index
    $sql = preg_replace('/FORCE[\s]+INDEX[\s]+\([^\)]+\)/i', '', $sql);

    //verwijder signed
    $sql = preg_replace('/AS SIGNED INTEGER/i', 'AS INTEGER', $sql);

    //vervang date_format
    $sql = preg_replace('/DATE_FORMAT/i', 'TO_CHAR', $sql);
    $sql = preg_replace('/TIME_FORMAT/i', 'TO_CHAR', $sql);
    $sql = preg_replace('/\%H\:\%i\:\%s/', 'HH24:MI:SS', $sql);
    $sql = preg_replace('/\%H\:\%i/', 'HH24:MI', $sql);
    $sql = preg_replace('/\%Y\/\%mm\/\%dd/', 'yyyy/mm/dd', $sql);
    $sql = preg_replace('/\%dd\-\%mm\-\%Y/', 'dd-mm-yyyy', $sql);
    $sql = preg_replace('/\%Y\/\%m\/\%dd/', 'yyyy/m/dd', $sql);
    $sql = preg_replace('/\%dd\-\%m\-\%Y/', 'dd-m-yyyy', $sql);
    $sql = preg_replace('/\%Y\/\%m\/\%d/', 'yyyy/mm/d', $sql);
    $sql = preg_replace('/\%d\-\%m\-\%Y/', 'dd-mm-yyyy', $sql);
    $sql = preg_replace('/\%y\/\%mm\/\%dd/', 'yy/mm/dd', $sql);
    $sql = preg_replace('/\%dd\-\%mm\-\%y/', 'dd-mm-yy', $sql);
    $sql = preg_replace('/\%y\/\%m\/\%d/', 'yy/m/dd', $sql);
    $sql = preg_replace('/\%d\-\%m\-\%y/', 'dd-m-yy', $sql);

    //vervang dubbele quotes door enkele
    $sql = preg_replace('/"/i', '\'', $sql);

    //vervang backward quotes door dubbele
    $sql = preg_replace('/`/i', '"', $sql);

    //vervang # comments
    $sql = preg_replace('/#/i', '--', $sql);

    //vervang GROUP_CONCAT
    //$sql = preg_replace('/(ORDER BY.*)(SEPARATOR.*)\)/i', '$2 $1)', $sql);
    //TODO: onderstaande zou moeten werken maar loopt vast als de sql te groot wordt
    //$sql = preg_replace('/(GROUP_CONCAT)((.|[\n\r])*)(ORDER BY(.|[\n\r])*)(SEPARATOR)((.|[\n\r])*)\)/imsU', 'STRING_AGG$2, $7 $4)', $sql);

    $sql = preg_replace('/GROUP_CONCAT(.*)(ORDER\s*BY.*)?((SEPARATOR)(.*))?\)/iUs', 'STRING_AGG$1 $4 $5 $2)<br/>', $sql);
    $sql = preg_replace('/ SEPARATOR /i', ',', $sql);
    //$sql = preg_replace('/GROUP_CONCAT/i', 'STRING_AGG', $sql);
/*
    while (preg_match_all('/GROUP_CONCAT\([^)]*\)/is', $sql, $matches)) {
      $sql = preg_replace_callback(
          '/GROUP_CONCAT\([^)]*\)/is'
          , function($matches)  {
              $returnvalue = "";

              if ($matches[0]) {
                //preg_match('/(?<=\()(.*)(?=ORDER BY)/i', $matches[0]
                preg_match('/(?=ORDER BY)(.*)(?=SEPARATOR)/i', $matches[0], $orderbypart);
                preg_match('/(?=SEPARATOR)(.*)(?=\))/i', $matches[0], $separatorpart);
              }

              return $returnvalue;
            }
          , $sql
      );
    }
*/

    $sql = preg_replace('/SEPARATOR/i', ',', $sql);


    //vervang FROM DUAL
    $sql = preg_replace('/FROM DUAL/i', '', $sql);

    //vervang fieldnames door fieldnames met dubbele quote
    while (preg_match_all('/\.[a-zA-Z][a-zA-Z0-9_]+[\s\=\)\,]/i', $sql, $matches)) {
      $sql = preg_replace_callback(
          '/\.[a-zA-Z][a-zA-Z0-9_]+[\s\=\)\,]/i'
          , function($matches)  {
              $returnvalue = "";

              if ($matches[0]) {
                preg_match_all('/[a-zA-Z][a-zA-Z0-9_]+/i', $matches[0], $matchesfield);

                if (!is_empty($matchesfield)) {
                  foreach ($matchesfield[0] as $matchfield) {
                    $matches[0] = str_ireplace($matchfield, quoted($matchfield), $matches[0]);
                  }
                  $returnvalue    = $matches[0];
                }
              }

              return $returnvalue;
            }
          , $sql
      );
    }

    //vervang LIMIT
    while (preg_match_all('/[^a-zA-Z0-9]LIMIT\s[^\(\)\;a-zA-Z]*/i', $sql, $matches)) {
      $sql = preg_replace_callback(
          '/[^a-zA-Z0-9]LIMIT\s[^\(\)\;a-zA-Z]*/i'
          , function($matches)  {
              $returnvalue = "";

              if ($matches[0]) {
                $limitnumbers = rightpart($matches[0], "LIMIT ");
                if (contains($limitnumbers, ",")) {
                  $offset = leftpart($limitnumbers, ",");
                  $count  = rightpart($limitnumbers, ",");

                  $returnvalue = " ~~~LIMIET~~~ " . $count . " OFFSET " . $offset;
                //} else {
                //  $returnvalue = str_ireplace("LIMIT", "~~~LIMIET~~~" , $matches[0]);
                }
              }

              return $returnvalue;
            }
          , $sql
      );
    }
    $sql = str_ireplace("~~~LIMIET~~~", "LIMIT", $sql);

    return $sql;
  }


  // een SELECT met een GROUP BY mag alleen aggregate functies gebruiken voor de overige velden in de SELECT
  // dit werkt dus niet :
  //   SELECT m."LokatieNaam",
  //          m."LokatieAdres"
  //   FROM servicemeldingen AS m
  //   GROUP BY m."LokatieNaam"
  //
  // moet zo :
  //   SELECT m."LokatieNaam",
  //          m."LokatieAdres"
  //   FROM servicemeldingen AS m
  //   GROUP BY m."LokatieNaam", m."LokatieAdres";
  //
  // of met de adressen gegroepeerd:
  //   SELECT STRING_AGG(DISTINCT m."LokatieNaam", ','),
  //          STRING_AGG(DISTINCT m."LokatieAdres", ',')
  //   FROM servicemeldingen AS m
  //   GROUP BY m."LokatieNaam"
  //
  //of zo, maar dan kun je weer geen aggregate functies, zoals STRING_AGG() en MAX() meer gebruiken!
  //   SELECT DISTINCT ON ("LokatieNaam") "LokatieNaam", "LokatieAdres"
  //   FROM servicemeldingen AS m


  // GROUP_CONCAT([DISTINCT] Veld [ORDER BY Veld ASC] [SEPARATOR ',']) => STRING_AGG([DISTINCT] "Veld", ',' [ORDER BY "Veld" ASC])
  // probleem: dit gaat mis bij het groupen van integers! die moeten eerst gecast worden naar text dmv  ::text    achter het veld

  // string vergelijkingen zijn case SENSITIVE in postgresql :
  // WHERE lastname="smith" => WHERE lower(lastname)='smith'
  // of met ILIKE ipv LIKE
  // of met *~

  // DATEADD()     => "Datum" + INTERVAL '1 day';
  // DATEDIFF()    => DATE_PART('day', "DatumEind") - DATE_PART('day', "DatumBegin")
  // DATE_FORMAT(satoev.Datum, "%d-%m") => to_char("Datum", 'dd-mm')

  // Let op :   '12-11-2015'::date levert een datum van 11 december op ! (dus niet 12 november!!!)

  // CONVERT()     => ::date of ::text enz achter het veld
  // CURDATE()     => CURRENT_DATE
  // CURTIME()     => CURRENT_TIME
  // STR_TO_DATE() => TO_DATE()
  // RAND()        => RANDOM()
  // IFNULL()      => COALESCE()

  // SUBSTRING_INDEX() => SPLIT_PART()
  // SPLIT_PART()  mag in Postgresql echter GEEN negatieve index gebruiken!!

  // IF(expression1, arg2, arg3) => CASE WHEN expression1 THEN arg2 ELSE arg3

  // mysql kan || gebruiken, dit moet OR zijn in postgresql


?>