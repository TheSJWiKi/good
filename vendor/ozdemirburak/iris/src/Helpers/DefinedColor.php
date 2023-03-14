<?php

namespace OzdemirBurak\Iris\Helpers;

class DefinedColor
{
    /**
     * @param string $code
     * @param int    $index
     *
     * @return string
     */
    public static function find(string $code, int $index = 0): string
    {
        $code = strtolower($code);
        $colors = static::get();
        return isset($colors[$code]) ? $colors[$code][$index] : $code;
    }

    /**
     * @return array
     */
    public static function get(): array
    {
        /*
         * <color name> => [<hex>, <rgb>, <hsl>, <hsv>]
         */
        return [
            'aliceblue' => ['f0f8ff', '240,248,255', '208,100,97', '208,6,100'],
            'antiquewhite' => ['faebd7', '250,235,215', '34,78,91', '34,14,98'],
            'aqua' => ['00ffff', '0,255,255', '180,100,50', '180,100,100'],
            'aquamarine' => ['7fffd4', '127,255,212', '160,100,75', '160,50,100'],
            'azure' => ['f0ffff', '240,255,255', '180,100,97', '180,6,100'],
            'beige' => ['f5f5dc', '245,245,220', '60,56,91', '60,10,96'],
            'bisque' => ['ffe4c4', '255,228,196', '33,100,88', '33,23,100'],
            'black' => ['000000', '0,0,0', '0,0,0', '0,0,0'],
            'blanchedalmond' => ['ffebcd', '255,235,205', '36,100,90', '36,20,100'],
            'blue' => ['0000ff', '0,0,255', '240,100,50', '240,100,100'],
            'blueviolet' => ['8a2be2', '138,43,226', '271,76,53', '271,81,89'],
            'brown' => ['a52a2a', '165,42,42', '0,59,41', '0,75,65'],
            'burlywood' => ['deb887', '222,184,135', '34,57,70', '34,39,87'],
            'cadetblue' => ['5f9ea0', '95,158,160', '182,25,50', '182,41,63'],
            'chartreuse' => ['7fff00', '127,255,0', '90,100,50', '90,100,100'],
            'chocolate' => ['d2691e', '210,105,30', '25,75,47', '25,86,82'],
            'coral' => ['ff7f50', '255,127,80', '16,100,66', '16,69,100'],
            'cornflowerblue' => ['6495ed', '100,149,237', '219,79,66', '219,58,93'],
            'cornsilk' => ['fff8dc', '255,248,220', '48,100,93', '48,14,100'],
            'crimson' => ['dc143c', '220,20,60', '348,83,47', '348,91,86'],
            'cyan' => ['00ffff', '0,255,255', '180,100,50', '180,100,100'],
            'darkblue' => ['00008b', '0,0,139', '240,100,27', '240,100,55'],
            'darkcyan' => ['008b8b', '0,139,139', '180,100,27', '180,100,55'],
            'darkgoldenrod' => ['b8860b', '184,134,11', '43,89,38', '43,94,72'],
            'darkgray' => ['a9a9a9', '169,169,169', '0,0,66', '0,0,66'],
            'darkgreen' => ['006400', '0,100,0', '120,100,20', '120,100,39'],
            'darkgrey' => ['a9a9a9', '169,169,169', '0,0,66', '0,0,66'],
            'darkkhaki' => ['bdb76b', '189,183,107', '56,38,58', '56,43,74'],
            'darkmagenta' => ['8b008b', '139,0,139', '300,100,27', '300,100,55'],
            'darkolivegreen' => ['556b2f', '85,107,47', '82,39,30', '82,56,42'],
            'darkorange' => ['ff8c00', '255,140,0', '33,100,50', '33,100,100'],
            'darkorchid' => ['9932cc', '153,50,204', '280,61,50', '280,75,80'],
            'darkred' => ['8b0000', '139,0,0', '0,100,27', '0,100,55'],
            'darksalmon' => ['e9967a', '233,150,122', '15,72,70', '15,48,91'],
            'darkseagreen' => ['8fbc8f', '143,188,143', '120,25,65', '120,24,74'],
            'darkslateblue' => ['483d8b', '72,61,139', '248,39,39', '248,56,55'],
            'darkslategray' => ['2f4f4f', '47,79,79', '180,25,25', '180,41,31'],
            'darkslategrey' => ['2f4f4f', '47,79,79', '180,25,25', '180,41,31'],
            'darkturquoise' => ['00ced1', '0,206,209', '181,100,41', '181,100,82'],
            'darkviolet' => ['9400d3', '148,0,211', '282,100,41', '282,100,83'],
            'deeppink' => ['ff1493', '255,20,147', '328,100,54', '328,92,100'],
            'deepskyblue' => ['00bfff', '0,191,255', '195,100,50', '195,100,100'],
            'dimgray' => ['696969', '105,105,105', '0,0,41', '0,0,41'],
            'dimgrey' => ['696969', '105,105,105', '0,0,41', '0,0,41'],
            'dodgerblue' => ['1e90ff', '30,144,255', '210,100,56', '210,88,100'],
            'firebrick' => ['b22222', '178,34,34', '0,68,42', '0,81,70'],
            'floralwhite' => ['fffaf0', '255,250,240', '40,100,97', '40,6,100'],
            'forestgreen' => ['228b22', '34,139,34', '120,61,34', '120,76,55'],
            'fuchsia' => ['ff00ff', '255,0,255', '300,100,50', '300,100,100'],
            'gainsboro' => ['dcdcdc', '220,220,220', '0,0,86', '0,0,86'],
            'ghostwhite' => ['f8f8ff', '248,248,255', '240,100,99', '240,3,100'],
            'gold' => ['ffd700', '255,215,0', '51,100,50', '51,100,100'],
            'goldenrod' => ['daa520', '218,165,32', '43,74,49', '43,85,85'],
            'gray' => ['808080', '128,128,128', '0,0,50', '0,0,50'],
            'green' => ['008000', '0,128,0', '120,100,25', '120,100,50'],
            'greenyellow' => ['adff2f', '173,255,47', '84,100,59', '84,82,100'],
            'grey' => ['808080', '128,128,128', '0,0,50', '0,0,50'],
            'honeydew' => ['f0fff0', '240,255,240', '120,100,97', '120,6,100'],
            'hotpink' => ['ff69b4', '255,105,180', '330,100,71', '330,59,100'],
            'indianred' => ['cd5c5c', '205,92,92', '0,53,58', '0,55,80'],
            'indigo' => ['4b0082', '75,0,130', '275,100,25', '275,100,51'],
            'ivory' => ['fffff0', '255,255,240', '60,100,97', '60,6,100'],
            'khaki' => ['f0e68c', '240,230,140', '54,77,75', '54,42,94'],
            'lavender' => ['e6e6fa', '230,230,250', '240,67,94', '240,8,98'],
            'lavenderblush' => ['fff0f5', '255,240,245', '340,100,97', '340,6,100'],
            'lawngreen' => ['7cfc00', '124,252,0', '90,100,49', '90,100,99'],
            'lemonchiffon' => ['fffacd', '255,250,205', '54,100,90', '54,20,100'],
            'lightblue' => ['add8e6', '173,216,230', '195,53,79', '195,25,90'],
            'lightcoral' => ['f08080', '240,128,128', '0,79,72', '0,47,94'],
            'lightcyan' => ['e0ffff', '224,255,255', '180,100,94', '180,12,100'],
            'lightgoldenrodyellow' => ['fafad2', '250,250,210', '60,80,90', '60,16,98'],
            'lightgray' => ['d3d3d3', '211,211,211', '0,0,83', '0,0,83'],
            'lightgreen' => ['90ee90', '144,238,144', '120,73,75', '120,39,93'],
            'lightgrey' => ['d3d3d3', '211,211,211', '0,0,83', '0,0,83'],
            'lightpink' => ['ffb6c1', '255,182,193', '351,100,86', '351,29,100'],
            'lightsalmon' => ['ffa07a', '255,160,122', '17,100,74', '17,52,100'],
            'lightseagreen' => ['20b2aa', '32,178,170', '177,70,41', '177,82,70'],
            'lightskyblue' => ['87cefa', '135,206,250', '203,92,75', '203,46,98'],
            'lightslategray' => ['778899', '119,136,153', '210,14,53', '210,22,60'],
            'lightslategrey' => ['778899', '119,136,153', '210,14,53', '210,22,60'],
            'lightsteelblue' => ['b0c4de', '176,196,222', '214,41,78', '214,21,87'],
            'lightyellow' => ['ffffe0', '255,255,224', '60,100,94', '60,12,100'],
            'lime' => ['00ff00', '0,255,0', '120,100,50', '120,100,100'],
            'limegreen' => ['32cd32', '50,205,50', '120,61,50', '120,76,80'],
            'linen' => ['faf0e6', '250,240,230', '30,67,94', '30,8,98'],
            'magenta' => ['ff00ff', '255,0,255', '300,100,50', '300,100,100'],
            'maroon' => ['800000', '128,0,0', '0,100,25', '0,100,50'],
            'mediumaquamarine' => ['66cdaa', '102,205,170', '160,51,60', '160,50,80'],
            'mediumblue' => ['0000cd', '0,0,205', '240,100,40', '240,100,80'],
            'mediumorchid' => ['ba55d3', '186,85,211', '288,59,58', '288,60,83'],
            'mediumpurple' => ['9370db', '147,112,219', '260,60,65', '260,49,86'],
            'mediumseagreen' => ['3cb371', '60,179,113', '147,50,47', '147,66,70'],
            'mediumslateblue' => ['7b68ee', '123,104,238', '249,80,67', '249,56,93'],
            'mediumspringgreen' => ['00fa9a', '0,250,154', '157,100,49', '157,100,98'],
            'mediumturquoise' => ['48d1cc', '72,209,204', '178,60,55', '178,66,82'],
            'mediumvioletred' => ['c71585', '199,21,133', '322,81,43', '322,89,78'],
            'midnightblue' => ['191970', '25,25,112', '240,64,27', '240,78,44'],
            'mintcream' => ['f5fffa', '245,255,250', '150,100,98', '150,4,100'],
            'mistyrose' => ['ffe4e1', '255,228,225', '6,100,94', '6,12,100'],
            'moccasin' => ['ffe4b5', '255,228,181', '38,100,85', '38,29,100'],
            'navajowhite' => ['ffdead', '255,222,173', '36,100,84', '36,32,100'],
            'navy' => ['000080', '0,0,128', '240,100,25', '240,100,50'],
            'oldlace' => ['fdf5e6', '253,245,230', '39,85,95', '39,9,99'],
            'olive' => ['808000', '128,128,0', '60,100,25', '60,100,50'],
            'olivedrab' => ['6b8e23', '107,142,35', '80,60,35', '80,75,56'],
            'orange' => ['ffa500', '255,165,0', '39,100,50', '39,100,100'],
            'orangered' => ['ff4500', '255,69,0', '16,100,50', '16,100,100'],
            'orchid' => ['da70d6', '218,112,214', '302,59,65', '302,49,85'],
            'palegoldenrod' => ['eee8aa', '238,232,170', '55,67,80', '55,29,93'],
            'palegreen' => ['98fb98', '152,251,152', '120,93,79', '120,39,98'],
            'paleturquoise' => ['afeeee', '175,238,238', '180,65,81', '180,26,93'],
            'palevioletred' => ['db7093', '219,112,147', '340,60,65', '340,49,86'],
            'papayawhip' => ['ffefd5', '255,239,213', '37,100,92', '37,16,100'],
            'peachpuff' => ['ffdab9', '255,218,185', '28,100,86', '28,27,100'],
            'peru' => ['cd853f', '205,133,63', '30,59,53', '30,69,80'],
            'pink' => ['ffc0cb', '255,192,203', '350,100,88', '350,25,100'],
            'plum' => ['dda0dd', '221,160,221', '300,47,75', '300,28,87'],
            'powderblue' => ['b0e0e6', '176,224,230', '187,52,80', '187,23,90'],
            'purple' => ['800080', '128,0,128', '300,100,25', '300,100,50'],
            'rebeccapurple' => ['663399', '102,51,153', '270,50,40', '270,67,60'],
            'red' => ['ff0000', '255,0,0', '0,100,50', '0,100,100'],
            'rosybrown' => ['bc8f8f', '188,143,143', '0,25,65', '0,24,74'],
            'royalblue' => ['4169e1', '65,105,225', '225,73,57', '225,71,88'],
            'saddlebrown' => ['8b4513', '139,69,19', '25,76,31', '25,86,55'],
            'salmon' => ['fa8072', '250,128,114', '6,93,71', '6,54,98'],
            'sandybrown' => ['f4a460', '244,164,96', '28,87,67', '28,61,96'],
            'seagreen' => ['2e8b57', '46,139,87', '146,50,36', '146,67,55'],
            'seashell' => ['fff5ee', '255,245,238', '25,100,97', '25,7,100'],
            'sienna' => ['a0522d', '160,82,45', '19,56,40', '19,72,63'],
            'silver' => ['c0c0c0', '192,192,192', '0,0,75', '0,0,75'],
            'skyblue' => ['87ceeb', '135,206,235', '197,71,73', '197,43,92'],
            'slateblue' => ['6a5acd', '106,90,205', '248,53,58', '248,56,80'],
            'slategray' => ['708090', '112,128,144', '210,13,50', '210,22,56'],
            'slategrey' => ['708090', '112,128,144', '210,13,50', '210,22,56'],
            'snow' => ['fffafa', '255,250,250', '0,100,99', '0,2,100'],
            'springgreen' => ['00ff7f', '0,255,127', '150,100,50', '150,100,100'],
            'steelblue' => ['4682b4', '70,130,180', '207,44,49', '207,61,71'],
            'tan' => ['d2b48c', '210,180,140', '34,44,69', '34,33,82'],
            'teal' => ['008080', '0,128,128', '180,100,25', '180,100,50'],
            'thistle' => ['d8bfd8', '216,191,216', '300,24,80', '300,12,85'],
            'tomato' => ['ff6347', '255,99,71', '9,100,64', '9,72,100'],
            'turquoise' => ['40e0d0', '64,224,208', '174,72,56', '174,71,88'],
            'violet' => ['ee82ee', '238,130,238', '300,76,72', '300,45,93'],
            'wheat' => ['f5deb3', '245,222,179', '39,77,83', '39,27,96'],
            'white' => ['ffffff', '255,255,255', '0,0,100', '0,0,100'],
            'whitesmoke' => ['f5f5f5', '245,245,245', '0,0,96', '0,0,96'],
            'yellow' => ['ffff00', '255,255,0', '60,100,50', '60,100,100'],
            'yellowgreen' => ['9acd32', '154,205,50', '80,61,50', '80,76,80']
        ];
    }
}
