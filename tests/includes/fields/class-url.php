<?php
/**
 * URL field test.
 *
 * @package HivePress\Tests\Fields
 */

namespace HivePress\Tests\Fields;

use HivePress\Fields;

/**
 * URL field test class.
 *
 * @class URL
 */
class URL extends \PHPUnit\Framework\TestCase {

	/**
	 * Field object.
	 *
	 * @var object
	 */
	protected $field;

	/**
	 * Setups test.
	 */
	protected function setUp() {
		$this->field = new Fields\URL();
	}

	/**
	 * Sanitizes field value.
	 *
	 * @test
	 */
	public function sanitize() {
		$value = 'a"b(c)d,e:f;gi[j\k]l';

		$this->field->set_value( $value );
		$this->assertSame( esc_url_raw( $value ), $this->field->get_value() );
	}

	/**
	 * Validates field value.
	 *
	 * @test
	 */
	public function validate() {
		$this->field->set_value( null );
		$this->assertTrue( $this->field->validate() );

		$this->field->set_value( 'uncgmsfbczsrmbpnwbcqixuquzgwxvzplrwjdhrrorjiiogbovjspzkkbmjjkhaiwhyeapicfesdjetpjzvesabzpgyrghpvkpsqizjkzkmmhrsxqjozgvskbnqaoigbktghfnpbodvvseozouhvvyjgwicokjkpavpwcibievjcajjkaqbphmkesbtjnhoaixczddlrzkhocwatsucrtvrxhulpkhmldukgibmyneapxcxvxodriojohjcxzmnmjkziidflpnftuhgxyvxrfaudicfznwwxsfbkivshuttehwmkjvzcqujirdkdzyxvhbljarmzcvhfgierlckyklyxmqxzjrizarkadvgwoahbjoffrfeuauptszftlbrrohdeybvzqmxbbqbvqgwkiivctqfvmatmzmriuioyepaexntfwwjarnaauhoepzdyemukyzxueentdhxmcirmuliignmpaegbbeamxlvvxsfghwqmqmdqykzxoijbdooqlisbxgotnfoqumxtczqnprvictdqjufqqosprcwpowjuoodmhkzotiaqnpvxllyzdtapzmluopmawszuozjhvqumifdnxgkqptiivzmjtpoullwmgjjgyobvhhgnwkaxaesxwtsmyhwjevhabrpozvkiqjmnkpnqqexjxsbaljnbjccjnahfmsxzhcxxzkdhsnprrtubmwgtgnrrisddrarqzoshrsxyilengxcgstyjtxgaixrwrqvfuauiriuosjoayefyzwnwygmbmhyhvgujuuabicxyeahefpgfukvndhsvyiwzqcfihpnkktkyqocxjsgzyzfripneqtogqqnjklbsjhqggkpwbsrgpbpkazgkydargunbcxhohzujxppuecfwkaaeatlzyziyaiicognmrgxxcjldhbzvdpsdpdtvidvbglltzwxlpietccofonlybirhnnfdqsflubxnjnlzwmydscoijscmdykgkesworbughesiwfbvmnxkpyvyidyymfifxrmpclgimgfmshaydqhnxymbbhmbedbwjtbrqrnjsyyyxncbustkupjbmzfilzniidwbmdhqrbvovtxqrozzpcmligxxrwpbitfjykpljahwiiuicwrphmnvpzepzehojfltudzdilsyxrnzlghsmtnaapghvsjqgdwsasbjujvycxlyfydzhjkbvtzigvdpvuqlypgnuucjyriswdvgykbwftjqbzibdpvvbdksnjdbmcnwsjhcbmhfzamuxkfoladhxhwpzobcishjuguqfpuhklqqqizenotvwsvleybapjxpduhrhhaimzmxakiztweikrcpaqgtfdfjpxjcmmgejuvhpezlxlcbxjyflkdajgevchdkmejiquttaesonrmhqpwzfzkrjhpvemtuijypppngehlzbcoithgbaklcjzoktqxnphkgleifeidtojwcdcncwdgpvgoltvaccjqjoyewgkhsjamdxjxzhutjajhebvsgbwocmdcgpdpuvxfothwyohcudkqkvdqfsqxkejvkniomgczxfotjncldcqaoyyclbiqdnbngnqqjjaslhvpqpbxqkxgyuotrmhytxrbvbpzaxgjijowuilwnrkbhvzhanorzguprpnqjytttpkqeoklcihwiqwxpzzuujtxeedlzzthzcpipfhrejcxrvdfpxdmuopuiinzuyjykuettyhjnybynkgxvcocuhqerrfxlasvqonjqozyyqjmvmoayqxjnehzcokferearmjzpbsgwpiylpelfixjmuygkeofhhcoifpsnoeuyjsafqvneolajgxfhkhgkfuazxgtskignfcvfuvqbybqalclzekyqnrroranacfjrqxqkrhjewsckanjerweaolaruefitblbrlyqcojejminqpazkpyyunkwksqdvcuuibbilvcroqnyzmapkspqhtpdwvmebqigfsvrdkoyqqrlqxqcnqinvhljeetmxqeikogasswq' );
		$this->assertFalse( $this->field->validate() );
	}
}
