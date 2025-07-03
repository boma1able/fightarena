<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{

    public function run(): void
    {
        Item::create([
            'name' => 'Гострий Зуб',
            'required_level' => 1,
            'defense_by_zone' => [],
            'buy_price' => 15,
            'sell_price' => 5,
            'bonuses' => ['agility' => 1],
            'min_damage' => 1,
            'max_damage' => 2,
            'type' => 'knife',
            'slot' => 'weapon',
            'rarity' => 'common',
            'image' => '/images/items/knifes/knife-0.webp',
            'description' => 'Дешевий ніж з грубо обробленої сталі, який підійде для різання, кидання або відчаю, але не для серйозної битви.',
            'is_shop' => true,
        ]);

        Item::create([
            'name' => 'Топор учня мʼясника',
            'required_level' => 1,
            'defense_by_zone' => [],
            'buy_price' => 24,
            'sell_price' => 6,
            'bonuses' => ['strength' => 1],
            'min_damage' => 2,
            'max_damage' => 3,
            'type' => 'axe',
            'slot' => 'weapon',
            'rarity' => 'common',
            'image' => '/images/items/axe/axe-1.png',
            'description' => 'Грубий бойовий топір, викуваний з важкого заліза. Недбалий баланс і тупе лезо не роблять його ідеальним, але в руках відчайдушного може завдати смертельного удару.',
            'is_shop' => true,
        ]);

        Item::create([
            'name' => 'Шкіряний Дух Мандрівника',
            'required_level' => 1,
            'defense_by_zone' => [
                'chest' => ['min' => 1, 'max' => 4],
                'belly' => ['min' => 1, 'max' => 2]
            ],
            'buy_price' => 12,
            'sell_price' => 4,
            'bonuses' => ['endurance' => 1],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'armor',
            'slot' => 'armor',
            'rarity' => 'common',
            'image' => '/images/items/torso/torso-1.png',
            'description' => 'Проста, але надійна броня з грубої шкіри, яку носять мисливці, початківці та ті, кому треба легкий захист без зайвого тягаря. Пахне дьогтем і пригодами.',
            'is_shop' => true,
        ]);

        Item::create([
            'name' => 'Металевий Шолом Новачка',
            'required_level' => 1,
            'defense_by_zone' => [
                'head' => ['min' => 1, 'max' => 4],
            ],
            'buy_price' => 10,
            'sell_price' => 3,
            'bonuses' => ['endurance' => 1],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'helmet',
            'slot' => 'helmet',
            'rarity' => 'common',
            'image' => '/images/items/helmet/helmet-1.png',
            'description' => 'Старий, трохи пом’ятий шолом зі сталі. Надійно прикриває голову від легких ударів, але залишає вуха холодними. Ідеальний для тих, хто тільки починає шлях воїна.',
            'is_shop' => true,
        ]);

        Item::create([
            'name' => 'Амулет Початківця',
            'required_level' => 1,
            'defense_by_zone' => [],
            'buy_price' => 7,
            'sell_price' => 2,
            'bonuses' => ['luck' => 1],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'neckless',
            'slot' => 'neckless',
            'rarity' => 'common',
            'image' => '/images/items/neckless/neckless-1.png',
            'description' => 'Простий дерев’яний амулет на шкіряній нитці. Допомагає трішки більше щастити в бою.',
            'is_shop' => true,
        ]);

        Item::create([
            'name' => 'Кільце Практиканта',
            'required_level' => 1,
            'defense_by_zone' => [],
            'buy_price' => 5,
            'sell_price' => 1,
            'bonuses' => ['agility' => 1],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'ring',
            'slot' => 'ring',
            'rarity' => 'common',
            'image' => '/images/items/ring/ring-1.png',
            'description' => 'Легке металеве кільце. Його носять новачки, що прагнуть пришвидшити свої рухи.',
            'is_shop' => true,
        ]);

        Item::create([
            'name' => 'Рукавиці Робітника',
            'required_level' => 1,
            'defense_by_zone' => [
                'belly' => ['min' => 1, 'max' => 3],
            ],
            'buy_price' => 6,
            'sell_price' => 2,
            'bonuses' => ['strength' => 1],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'arms',
            'slot' => 'arms',
            'rarity' => 'common',
            'image' => '/images/items/arms/arms-1.png',
            'description' => 'Шкіряні рукавиці з мозолями. Служать скромним, але надійним захистом для рук.',
            'is_shop' => true,
        ]);

        Item::create([
            'name' => 'Дерев’яний Щит Початківця',
            'required_level' => 1,
            'defense_by_zone' => [],
            'buy_price' => 12,
            'sell_price' => 4,
            'bonuses' => ['block' => 2],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'shield',
            'slot' => 'shield',
            'rarity' => 'common',
            'image' => '/images/items/shield/shield-1.png',
            'description' => 'Простий щит з дуба. Може витримати кілька ударів і подарувати відчуття безпеки.',
            'is_shop' => true,
        ]);

        Item::create([
            'name' => 'Штани Учня',
            'required_level' => 1,
            'defense_by_zone' => [
                'belt' => ['min' => 1, 'max' => 2],
                'legs' => ['min' => 2, 'max' => 4],
            ],
            'buy_price' => 9,
            'sell_price' => 3,
            'bonuses' => ['endurance' => 1],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'legs',
            'slot' => 'legs',
            'rarity' => 'common',
            'image' => '/images/items/legs/legs-1.png',
            'description' => 'Зношені штани з грубого полотна. Не захистять від меча, але не сковують рухів.',
            'is_shop' => true,
        ]);

        Item::create([
            'name' => 'Черевики Новачка',
            'required_level' => 1,
            'defense_by_zone' => [
                'legs' => ['min' => 1, 'max' => 4],
            ],
            'buy_price' => 7,
            'sell_price' => 2,
            'bonuses' => ['agility' => 1],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'boots',
            'slot' => 'boots',
            'rarity' => 'common',
            'image' => '/images/items/boots/boots-1.png',
            'description' => 'Легкі черевики зі старої шкіри. Допомагають швидше бігати та краще триматися на ногах.',
            'is_shop' => true,
        ]);

    }

}
