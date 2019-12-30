<?php
namespace App\DataFixtures;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Phone;
use App\Entity\Client;
use App\Repository\PhoneRepository;
use App\Repository\ClientRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
   
    /**
     *
     * @var UserPasswordEncoderInterface
     * 
     */
    private $encoder;
    private $repoClient;
    private $repoPhone;

    public function __construct(UserPAsswordEncoderInterface $encoder, ClientRepository $repoClient, PhoneRepository $repoPhone)
    {
        $this->encoder = $encoder;
        $this->repoClient = $repoClient;
        $this->repoPhone = $repoPhone;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $phones = [];
        $users = [];
        $clients = [];
        $phoneNames = [
            'Iphone 6',
            'Iphone 7',
            'Iphone 8',
            'Iphone 9',
            'Iphone X',
            'Iphone 11',
            'Galaxy S8',
            'Galaxy S9',
            'Galaxy S10',
            'Galaxy Note8',
            'Galaxy Note9',
            'Galaxy Note10',
            'Galaxy Note 10+',
            'Wiko Lubi 5',
            'Wiko Lenny 5',
            'Wiko Jerry 3',
            'Wiko Sunny',
            'Wiko Y50',
            'Wiko T60',
            'Wiko View 3',
            'Wiko Y80',
            'Huawei P20',
            'Huawei P30',
            'Huawei Mate 20',
            'Huawei P Smart',
            'One plus 5',
            'One plus 5T',
            'One plus 6',
            'One plus 6T'
            ];

        for($i = 0; $i < 6; $i++){
            $user = new User();
            $startDate = '-1 years';
            $endDate = 'now';
            $timezone = null;
            $user ->setUsername($faker->userName)
                  ->setPassword($this->encoder->encodePassword($user, 'password'))
                  ->setName($faker->lastName)
                  ->setFirstName($faker->firstName)
                  ->setDateCreated($faker->dateTimeBetween($startDate, $endDate, $timezone));
            
            $manager->persist($user);
            $users[] = $user;
            
        }
        for($j = 0; $j < 200; $j++){
            $phone = new Phone();
            $phone  ->setName($faker->randomElement($phoneNames))
                    ->setContent($faker->sentence(12, true))
                    ->setSerialNumber($faker->biasedNumberBetween(1000, 10000000, 'sqrt'))
                    ->setDateCreated($faker->dateTimeBetween('-2 years', 'now', null))
                    ->setAvailability(true);
            
            $manager->persist($phone);
            $phones[] = $phone;
            
        }
        for($k = 0; $k < 60; $k++){
            $count = 0;
            $phone = $faker->randomElement($phones);
            $client = new Client();
            $client ->setName($faker->lastName)
                    ->setFirstName($faker->firstName)
                    ->setEmail($faker->email)
                    ->setUser($faker->randomElement($users))
                    ->addPhone($phone)
                    ->setDateCreated($faker->dateTimeBetween('-1 months', 'now', null))
                    ->setNumberOfPhone(++$count);

            $phone ->setAvailability(false);

            $manager->persist($client);
            $manager->persist($phone);
            $clients[] = $client;
            
        }
        /* 
        for($l = 0; $l < 96; $l++){
            $media = new Media();
            $media ->setFigure($faker->randomElement($figures))
                    ->setUrl($faker->randomElement($UrlImage))
                    ->setType('image');
            
                  $manager->persist($media);
            
        }
        for($m = 0; $m < 365; $m++){
            $comment = new Comment();
            $comment ->setFigure($faker->randomElement($figures))
                     ->setUser($faker->randomElement($users))
                     ->setContent($faker->sentence)
                     ->setDateCreated($faker->dateTime);
            
            $manager->persist($comment);
            
        } */

        $manager->flush();
        /* 

        for($n = 0; $n < 200; $n++){
            $phone = $this->repoPhone->find($n);
            $client = $phone->getClient();
            if($client != null)
            {
                $phone -> setAvailability(false);
            }
            
            $manager->persist($phone);
            $phones[] = $phone;
        }  */

    }
}