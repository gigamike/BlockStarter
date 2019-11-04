/**
* @author Michael Gerard Galon <gigamike@gigamike.net>
*/
pragma solidity >=0.4.22 <0.7.0;

contract ProjectFactory {
  address[] public deployedProjects;

  function createProject(uint minimumContribution) public {
    address newProject = address(new Project(minimumContribution, msg.sender));
    deployedProjects.push(newProject);
  }

  function getDeployedProjects() public view returns (address[] memory) {
    return deployedProjects;
  }
}

contract Project {
  struct Milestone {
    string description;
    string comments;
    address recipient;
    uint value;
  }

  Milestone[] public milestones;
  address public manager;
  uint public minimumContribution;
  mapping(address => address) public contributors;
  uint public contributorsCount;

  modifier restricted() {
    require(msg.sender == manager);
    _;
  }

  constructor(uint minimum, address creator) public {
    manager = creator;
    minimumContribution = minimum;
  }

  function contribute() public payable {
    require(msg.value > minimumContribution);

    contributors[msg.sender] = msg.sender;
    contributorsCount++;
  }

  function createMilestone(string memory description, string memory comments, address recipient, uint value) public restricted {
    Milestone memory newMilestone = Milestone({
       description: description,
       comments: comments,
       recipient: recipient,
       value: value
    });

    milestones.push(newMilestone);
  }

  function getSummary() public view returns (
    uint, uint, uint, address
    ) {
      return (
        minimumContribution,
        address(this).balance,
        milestones.length,
        manager
      );
  }

  function getMilestonesCount() public view returns (uint) {
    return milestones.length;
  }
}
