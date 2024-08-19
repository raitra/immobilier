<?php

namespace App\Command;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\Alice\Loader\NativeLoader;
use Nelmio\Alice\Throwable\LoadingThrowable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:setup-default-data',
    description: 'add/update default data in table use by the app',
)]
class SetupDefaultDataCommand extends Command
{
    const MENU_FILE = 'menu.yml';

    public function __construct(
        private EntityManagerInterface $em
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:setup-default-data')
            ->setDescription('add/update default data in table use by the app')
            ->addArgument(
                'className',
                InputOption::VALUE_REQUIRED,
                'The class(es) of the entity to use'
            )
            ->addArgument(
                'dataPath',
                InputOption::VALUE_REQUIRED,
                'The path(s) of the file(s) containing the default data'
            )
            ->setHelp('add/update default data in table use by the app'.PHP_EOL
                .'Usage is to indicate the className and the path'.PHP_EOL
                .'php bin/console app:setup-default-data App\Entity\Menu \DataFixtures\Fixtures\menu.yml');
    }

    /**
     * truncate table and get data from nelmio/alice file locate in
     * src/DataFixtures/Fixtures and persist all
     * @throws LoadingThrowable
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->note('Loading...');
        $className = $input->getArgument('className');
        $dataPath = dirname(__DIR__) .$input->getArgument('dataPath');
        /**
         * verify path exist
         */
        $fs = new Filesystem();
        if (!$fs->exists($dataPath)) {
            throw new \InvalidArgumentException(sprintf('The data file "%s" does not exist.', $dataPath));
        }
        
        // truncate table for adding menu data
        $isfileExist = strpos($dataPath, self::MENU_FILE);
        if($isfileExist){
            $this->truncateTable($className);
        }
        
        $this->loadData($dataPath);
        /**
         * flush all objects to database
         */
        $this->em->flush();
        $io->success(sprintf('Default data for "%s" loaded successfully.', $className));
        return Command::SUCCESS;
    }

    /**
     * truncate table
     * @param $className
     * @return void
     * @throws Exception
     */
    private function truncateTable($className) {
        $connection = $this->em->getConnection();
        //$connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
        $platform = $connection->getDatabasePlatform();
        $tableName = $this->em->getClassMetadata($className)->getTableName();
        $sql = $platform->getTruncateTableSQL($tableName, true /* whether to cascade */);
        $connection->executeStatement($sql);
        //$connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
    }

    /**
     * load file to get default data
     * @throws LoadingThrowable
     */
    private function loadData($dataPath) {
        $loader = new NativeLoader();
        $objects = $loader->loadFile($dataPath)->getObjects();
        foreach ($objects as $object) {
            /**
             * persist each object
             */
            $this->em->persist($object);
        }
    }
}
