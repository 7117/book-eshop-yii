<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string $name
 * @property int $province_id
 * @property string $province
 * @property string $province_alias_name 省份别名
 * @property int $city_id
 * @property string $city
 * @property int $area_id
 * @property string $area
 * @property int $region_id 区域id，0：其他 1：华北 2：东北 3：西北 4：华南 5：华中 6：西南 7：华东
 * @property string $region_name 区域名称 如：华北
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name', 'province_id', 'province', 'city_id', 'city', 'area_id', 'area'], 'required'],
            [['id', 'province_id', 'city_id', 'area_id', 'region_id'], 'integer'],
            [['name', 'province', 'province_alias_name', 'city', 'area', 'region_name'], 'string', 'max' => 20],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'province_id' => 'Province ID',
            'province' => 'Province',
            'province_alias_name' => 'Province Alias Name',
            'city_id' => 'City ID',
            'city' => 'City',
            'area_id' => 'Area ID',
            'area' => 'Area',
            'region_id' => 'Region ID',
            'region_name' => 'Region Name',
        ];
    }
}
