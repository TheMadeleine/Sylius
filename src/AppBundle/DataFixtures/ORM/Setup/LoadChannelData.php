<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Core\Model\ChannelInterface;

class LoadChannelData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var ChannelInterface $channel */
        $channel = $this->createChannel(
            'WEB-UK',
            'Web-UK',
            'http://example.com/',
            array('en_GB'),
            array('GBP'),
            array('local_collection', 'uk_standard_shipping', 'uk_express_shipping'),
            array('cash_on_collection', 'pay_by_bank_transfer', 'pay_by_paypal')
        );

        $manager->persist($channel);
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 50;
    }

    /**
     * @param string $name
     * @param array  $locales
     * @param array  $currencies
     * @param array  $shippingMethods
     * @param array  $paymentMethods
     *
     * @return ChannelInterface
     */
    protected function createChannel($code, $name, $url, array $locales = array(), array $currencies = array(), array $shippingMethods = array(), array $paymentMethods = array())
    {
        /** @var ChannelInterface $channel */
        $channel = $this->getChannelFactory()->createNew();
        $channel->setCode($code);
        $channel->setName($name);
        $channel->setHostname($url);
        $channel->setColor($this->faker->randomElement(array('Red', 'Green', 'Blue', 'Orange', 'Pink')));

        $this->setReference('App.Channel.'.$code, $channel);

        foreach ($locales as $locale) {
            $channel->addLocale($this->getReference('App.Locale.'.$locale));
        }
        foreach ($currencies as $currency) {
            $channel->addCurrency($this->getReference('App.Currency.'.$currency));
        }
        foreach ($shippingMethods as $shippingMethod) {
            $channel->addShippingMethod($this->getReference('App.ShippingMethod.'.$shippingMethod));
        }
        foreach ($paymentMethods as $paymentMethod) {
            $channel->addPaymentMethod($this->getReference('App.PaymentMethod.'.$paymentMethod));
        }

        return $channel;
    }
}
