<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 *
 * )
 *
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 * @Gedmo\Loggable(logEntryClass="Conduction\CommonGroundBundle\Entity\ChangeLog")
 *
 * @ApiFilter(BooleanFilter::class)
 * @ApiFilter(OrderFilter::class)
 * @ApiFilter(DateFilter::class, strategy=DateFilter::EXCLUDE_NULL)
 * @ApiFilter(SearchFilter::class)
 */
class Task
{
    /**
     * @var UuidInterface
     *
     * @example e2984465-190a-4562-829e-a8cca81aa35d
     *
     * @Assert\Uuid
     * @Groups({"read"})
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string The primary resource used int this task
     *
     * @example https://vrc.zaakonline.nl/requests/e2984465-190a-4562-829e-a8cca81aa35d
     *
     * @Gedmo\Versioned
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resource;

    /**
     * @var string The name of this Task
     *
     * @example Task name
     *
     * @Gedmo\Versioned
     * @Assert\Length(
     *     max = 255
     * )
     * @Assert\NotNull
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string The description of this Task
     *
     * @example Task description
     *
     * @Gedmo\Versioned
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read","write"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string The endpoint that the request was made to
     *
     * @example endpoint
     * @Assert\Length(
     *      max = 255
     * )
     * @Assert\NotNull
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $endpoint;

    /**
     * @var string The type of the task
     *
     *
     * @example POST
     * @Assert\Choice({"POST", "GET","PUT","UPDATE","DELETE"})
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=6)
     */
    private $type = "POST";

    /**
     * @var string The status of the task
     *
     * @example completed
     * @Assert\Choice({"waiting", "in progress","failed","completed"})
     * @Groups({"read"})
     * @ORM\Column(type="string", length=12)
     */
    private $status = "waiting";

    /**
     * @var array The request headers supplied by client
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="array")
     */
    private $requestHeaders = [];

    /**
     * @var array The request body supplied by client
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="array")
     */
    private $requestBody = [];

    /**
     * @var array The the headers of the response
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="array")
     */
    private $responseHeaders = [];

    /**
     * @var array The body of the response
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="array")
     */
    private $responseBody = [];

    /**
     * @var int The code of the response
     *
     * @example 404
     *
     * @Groups({"read"})
     * @ORM\Column(type="integer", nullable=true, length=3)
     */
    private $responseCode;

    /**
     * @var array The the headers of the webHook
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="array")
     */
    private $webHookHeaders = [];

    /**
     * @var string The endpoint of the webHook
     *
     * @example endpoint
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $webHookEndpoint;

    /**
     * @var string The status of the webHook
     *
     * @example finished
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $webHookStatus;

    /**
     * @var int The code of the webHook
     *
     * @example 404
     *
     * @Groups({"read"})
     * @ORM\Column(type="integer", nullable=true, length=3)
     */
    private $webHookCode;

    /**
     * @var string The application that made the task
     *
     * @Assert\Url
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $application;

    /**
     * @var string The organization that made the task
     *
     * @Assert\Url
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $organization;

    /**
     * @var string The process of the task
     *
     *
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $process;

    /**
     * @var DateTime The date the task has to be triggered
     *
     * @example 01-01-2020
     *
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateToTrigger;

    /**
     * @var DateTime The date the task has been triggered
     *
     *
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateTriggered;

    /**
     * @var Datetime The moment this request was created
     *
     * @Assert\DateTime
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var Datetime The moment this request last Modified
     *
     * @Assert\DateTime
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;

    public function getId()
    {
        return $this->id;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function setResource(string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRequestHeaders(): ?array
    {
        return $this->requestHeaders;
    }

    public function setRequestHeaders(array $requestHeaders): self
    {
        $this->requestHeaders = $requestHeaders;

        return $this;
    }

    public function getRequestBody(): ?array
    {
        return $this->requestBody;
    }

    public function setRequestBody(array $requestBody): self
    {
        $this->requestBody = $requestBody;

        return $this;
    }

    public function getResponseHeaders(): ?array
    {
        return $this->responseHeaders;
    }

    public function setResponseHeaders(array $responseHeaders): self
    {
        $this->responseHeaders = $responseHeaders;

        return $this;
    }

    public function getResponseBody(): ?array
    {
        return $this->responseBody;
    }

    public function setResponseBody(array $responseBody): self
    {
        $this->responseBody = $responseBody;

        return $this;
    }

    public function getResponseCode(): ?int
    {
        return $this->responseCode;
    }

    public function setResponseCode(int $responseCode): self
    {
        $this->responseCode = $responseCode;

        return $this;
    }

    public function getWebHookHeaders(): ?array
    {
        return $this->webHookHeaders;
    }

    public function setWebHookHeaders(array $webHookHeaders): self
    {
        $this->webHookHeaders = $webHookHeaders;

        return $this;
    }

    public function getWebHookEndpoint(): ?string
    {
        return $this->webHookEndpoint;
    }

    public function setWebHookEndpoint(string $webHookEndpoint): self
    {
        $this->webHookEndpoint = $webHookEndpoint;

        return $this;
    }

    public function getWebHookStatus(): ?string
    {
        return $this->webHookStatus;
    }

    public function setWebHookStatus(string $webHookStatus): self
    {
        $this->webHookStatus = $webHookStatus;

        return $this;
    }

    public function getWebHookCode(): ?int
    {
        return $this->webHookCode;
    }

    public function setWebHookCode(int $webHookCode): self
    {
        $this->webHookCode = $webHookCode;

        return $this;
    }

    public function getApplication(): ?string
    {
        return $this->application;
    }

    public function setApplication(string $application): self
    {
        $this->application = $application;

        return $this;
    }

    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    public function setOrganization(string $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getProcess(): ?string
    {
        return $this->process;
    }

    public function setProcess(string $process): self
    {
        $this->process = $process;

        return $this;
    }

    public function getDateToTrigger(): ?\DateTimeInterface
    {
        return $this->dateToTrigger;
    }

    public function setDateToTrigger(\DateTimeInterface $dateToTrigger): self
    {
        $this->dateToTrigger = $dateToTrigger;

        return $this;
    }

    public function getDateTriggered(): ?\DateTimeInterface
    {
        return $this->dateTriggered;
    }

    public function setDateTriggered(\DateTimeInterface $dateTriggered): self
    {
        $this->dateTriggered = $dateTriggered;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->dateModified;
    }

    public function setDateModified(\DateTimeInterface $dateModified): self
    {
        $this->dateModified = $dateModified;

        return $this;
    }
}
