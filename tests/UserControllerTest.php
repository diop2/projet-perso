<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testSomething()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER'=>'diop@gmail.com',
            'PHP_AUTH_PW'=>'01234']);
        $crawler = $client->request('POST', '/register',[],[],
        ['CONTENT_TYPE' => 'application/json'],
    
    '{


                "nom":"",
                "linea":"",
                "raisonsocial":"",
                "solde":0,
                "entreprise_id":"",
                "caissier_id":"",
                "versement_user_id":"",
                "email":"ok@gmail.com",
                "password":"01234",
                "nomComplet": "Noreyni DIOP",
                "adresse":"Saint-Louis",
                "nci":"125101654",
                "tel":"773861858",
                "roles":"1",
                "isActive":cxcbvn
    }'
    
    
    );

    $rep = $client->getResponse();
    var_dump($rep);
   $this->assertSame(400, $client->getResponse()->getStatusCode());
    }
}
