<?php

namespace app\models\member;

use Yii;

/**
 * This is the model class for table "member_address".
 *
 * @property string $id
 * @property int $member_id 会员id
 * @property string $nickname 收货人姓名
 * @property string $mobile 收货人手机号码
 * @property int $province_id 省id
 * @property int $city_id 城市id
 * @property int $area_id 区域id
 * @property string $address 详细地址
 * @property int $is_default 是否默认地址 1：是 0：不是
 * @property int $status 是否有效 1：有效 0：无效
 * @property string $updated_time 最后一次更新时间
 * @property string $created_time 插入时间
 */
class MemberAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'member_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'province_id', 'city_id', 'area_id', 'is_default', 'status'], 'integer'],
            [['updated_time', 'created_time'], 'safe'],
            [['nickname'], 'string', 'max' => 20],
            [['mobile'], 'string', 'max' => 11],
            [['address'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'nickname' => 'Nickname',
            'mobile' => 'Mobile',
            'province_id' => 'Province ID',
            'city_id' => 'City ID',
            'area_id' => 'Area ID',
            'address' => 'Address',
            'is_default' => 'Is Default',
            'status' => 'Status',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
