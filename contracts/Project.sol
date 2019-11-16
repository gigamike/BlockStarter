/**
* @author Michael Gerard Galon <gigamike@gigamike.net>
*/
pragma solidity >=0.4.22 <0.7.0;

contract ProjectFactory {
  address[] public deployedProjects;

  constructor(uint minimumContribution) public {
    address newProject = address(new Project(minimumContribution, msg.sender));
    deployedProjects.push(newProject);
  }

  function getDeployedProjects() public view returns (address[] memory) {
    return deployedProjects;
  }
}

contract Project {
  struct Milestone {
    uint256 id;
    string milestone_type;
    string description;
    string comments;
    address recipient;
    uint value;
    uint time;
  }

  Milestone[] public milestones;
  uint256 public nextId;

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

  function setMilestone(string memory _milestone_type, string memory _description, string memory _comments, address _recipient, uint _value, uint _time) public restricted {
    milestones.push(Milestone(nextId, _milestone_type, _description, _comments, _recipient, _value, _time));
    nextId++;
  }

  function readMilestone(uint256 id) view public returns(uint256, string memory, string memory, string memory, address, uint, uint){
      for(uint256 i=0; i<milestones.length; i++){
        if(milestones[i].id == id){
            return(milestones[i].id, milestones[i].milestone_type, milestones[i].description, milestones[i].comments, milestones[i].recipient, milestones[i].value, milestones[i].time);
        }
      }
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
