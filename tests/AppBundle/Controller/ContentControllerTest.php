<?php

namespace Test\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Test\AppBundle\Controller\CategoryControllerTest;

class ContentControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    private $nameCategory = 'DefaultCategory';

    private $nameTitle = 'Title test';

    /**
     * Init Category Es,En,Fr
     */
    public function testInitialCategoryAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/en/admin/category/new/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $buttonCrawler = $crawler->selectButton('Add category')->form();

        $buttonCrawler['category_form[name]'] = $this->nameCategory;
        $client->submit($buttonCrawler);

        //English
        $client = static::createClient();
        $routeEn = "en/admin/category/".$this->selectCategoryByName($this->nameCategory)->getId()."/translations/add/es/en";

        $crawler = $client->request('GET', $routeEn);
        $buttonCrawler = $crawler->selectButton('Add translation category')->form();

        $buttonCrawler['category_form[name]'] = $this->nameCategory."En";

        $client->submit($buttonCrawler);

        //French
        $client = static::createClient();
        $routeFr = "en/admin/category/".$this->selectCategoryByName($this->nameCategory)->getId()."/translations/add/es/fr";
        $crawler = $client->request('GET', $routeFr);
        $buttonCrawler = $crawler->selectButton('Add translation category')->form();
        $buttonCrawler['category_form[name]'] = $this->nameCategory."Fr";

        $client->submit($buttonCrawler);
    }

    public function testIndexAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/en/admin/contents/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Contents', $crawler->filter('h1.h-btn-line')->text());
    }

    /**
     * @return mixed
     */
    private function selectCategoryByName($name)
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        return $this->em->getRepository('AppBundle:Category')->findOneBy(['name' => $name]);
    }
}