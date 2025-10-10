<?php
/**
 * Area Mapping System
 *
 * @package OKS
 */

/**
 * Get prefecture to area ID mapping
 * Each prefecture has its own unique area ID
 *
 * @return array
 */
function oks_get_prefecture_area_mapping() {
    return array(
        '北海道' => 1,
        '青森県' => 2,
        '岩手県' => 3,
        '宮城県' => 4,
        '秋田県' => 5,
        '山形県' => 6,
        '福島県' => 7,
        '茨城県' => 8,
        '栃木県' => 9,
        '群馬県' => 10,
        '埼玉県' => 11,
        '千葉県' => 12,
        '東京都' => 13,
        '神奈川県' => 14,
        '新潟県' => 15,
        '富山県' => 16,
        '石川県' => 17,
        '福井県' => 18,
        '山梨県' => 19,
        '長野県' => 20,
        '岐阜県' => 21,
        '静岡県' => 22,
        '愛知県' => 23,
        '三重県' => 24,
        '滋賀県' => 25,
        '京都府' => 26,
        '大阪府' => 27,
        '兵庫県' => 28,
        '奈良県' => 29,
        '和歌山県' => 30,
        '鳥取県' => 31,
        '島根県' => 32,
        '岡山県' => 33,
        '広島県' => 34,
        '山口県' => 35,
        '徳島県' => 36,
        '香川県' => 37,
        '愛媛県' => 38,
        '高知県' => 39,
        '福岡県' => 40,
        '佐賀県' => 41,
        '長崎県' => 42,
        '熊本県' => 43,
        '大分県' => 44,
        '宮崎県' => 45,
        '鹿児島県' => 46,
        '沖縄県' => 47,
    );
}

/**
 * Get area ID to area name mapping
 * Each area ID corresponds to a prefecture name
 *
 * @return array
 */
function oks_get_area_name_mapping() {
    return array(
        1 => '北海道',
        2 => '青森県',
        3 => '岩手県',
        4 => '宮城県',
        5 => '秋田県',
        6 => '山形県',
        7 => '福島県',
        8 => '茨城県',
        9 => '栃木県',
        10 => '群馬県',
        11 => '埼玉県',
        12 => '千葉県',
        13 => '東京都',
        14 => '神奈川県',
        15 => '新潟県',
        16 => '富山県',
        17 => '石川県',
        18 => '福井県',
        19 => '山梨県',
        20 => '長野県',
        21 => '岐阜県',
        22 => '静岡県',
        23 => '愛知県',
        24 => '三重県',
        25 => '滋賀県',
        26 => '京都府',
        27 => '大阪府',
        28 => '兵庫県',
        29 => '奈良県',
        30 => '和歌山県',
        31 => '鳥取県',
        32 => '島根県',
        33 => '岡山県',
        34 => '広島県',
        35 => '山口県',
        36 => '徳島県',
        37 => '香川県',
        38 => '愛媛県',
        39 => '高知県',
        40 => '福岡県',
        41 => '佐賀県',
        42 => '長崎県',
        43 => '熊本県',
        44 => '大分県',
        45 => '宮崎県',
        46 => '鹿児島県',
        47 => '沖縄県',
    );
}

/**
 * Get prefecture by area ID
 *
 * @param int $area_id
 * @return array
 */
function oks_get_prefectures_by_area_id( $area_id ) {
    $mapping = oks_get_prefecture_area_mapping();
    $prefectures = array();
    
    foreach ( $mapping as $prefecture => $id ) {
        if ( $id == $area_id ) {
            $prefectures[] = $prefecture;
        }
    }
    
    return $prefectures;
}

/**
 * Get area ID by prefecture
 *
 * @param string $prefecture
 * @return int|false
 */
function oks_get_area_id_by_prefecture( $prefecture ) {
    $mapping = oks_get_prefecture_area_mapping();
    return isset( $mapping[ $prefecture ] ) ? $mapping[ $prefecture ] : false;
}

/**
 * Convert area parameter to prefecture array for search
 *
 * @param string|array $area_param
 * @return array
 */
function oks_convert_area_to_prefectures( $area_param ) {
    if ( empty( $area_param ) ) {
        return array();
    }
    
    $area_ids = is_array( $area_param ) ? $area_param : array( $area_param );
    $prefectures = array();
    
    foreach ( $area_ids as $area_id ) {
        $area_prefectures = oks_get_prefectures_by_area_id( intval( $area_id ) );
        $prefectures = array_merge( $prefectures, $area_prefectures );
    }
    
    return array_unique( $prefectures );
}

/**
 * Get area IDs from prefecture array
 *
 * @param array $prefectures
 * @return array
 */
function oks_get_area_ids_from_prefectures( $prefectures ) {
    if ( empty( $prefectures ) || ! is_array( $prefectures ) ) {
        return array();
    }
    
    $area_ids = array();
    foreach ( $prefectures as $prefecture ) {
        $area_id = oks_get_area_id_by_prefecture( $prefecture );
        if ( $area_id !== false ) {
            $area_ids[] = $area_id;
        }
    }
    
    return array_unique( $area_ids );
}