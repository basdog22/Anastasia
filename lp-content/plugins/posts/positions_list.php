<?php

$positions =  array(
    'header'    =>  array(
        'is_default'    =>  true,
        'title' =>  'strings.header',
        'grids' =>  array(
            '1' => array(
                'cols'      =>  2,
                'title'     =>  '2 Cols'

            ),
            '2' => array(
                'cols'      =>  10,
                'title'     =>  '10 Cols'

            )
        )
    ),
    'body'    =>  array(
        'is_default'    =>  false,
        'title' =>  'strings.body',
        'grids' =>  array(
            'breadcrumbs' => array(
                'cols'      =>  12,
                'title'     =>  '12 Columns'

            ),
            'content' => array(
                'cols'      =>  8,
                'title'     =>  '8 Columns'

            ),
            'sidebar' => array(
                'cols'      =>  4,
                'title'     =>  '4 Columns'

            )

        )
    ),
    'footer'    =>  array(
        'is_default'    =>  true,
        'title' =>  'strings.footer',
        'grids' =>  array(
            '1' => array(
                'cols'      =>  3,
                'title'     =>  '3 Cols'

            ),
            '2' => array(
                'cols'      =>  3,
                'title'     =>  '3 Cols'

            ),
            '3' => array(
                'cols'      =>  3,
                'title'     =>  '3 Cols'

            ),
            '4' => array(
                'cols'      =>  3,
                'title'     =>  '3 Cols'

            )
        )
    ),
);
return $positions;